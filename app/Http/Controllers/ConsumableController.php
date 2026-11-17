<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableRequest;
use App\Models\ConsumableTransaction;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ConsumableController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Consumable::with('location');
        $user = auth()->user();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhereHas('location', function ($ql) use ($search) {
                      $ql->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $consumables = $query->latest()->paginate(12)->withQueryString();

        // 1. Riwayat Permintaan Barang Saya (untuk User / Waka / Peminjam)
        $myRequests = ConsumableRequest::where('user_id', $user->id)
            ->with(['consumable.location', 'approver'])
            ->latest()
            ->take(15)
            ->get();

        // 2. Daftar Pengajuan Masuk untuk Admin / Super Admin (sesuai wewenang lokasi)
        $pendingRequests = collect();
        if ($user->isAdmin() || $user->isLaboran() || $user->isTeknisi()) {
            $reqQuery = ConsumableRequest::where('status', 'pending')
                ->with(['consumable.location', 'user']);

            if ($user->role === 'admin') {
                if ($user->location_id) {
                    $adminLocId = $user->location_id;
                    $reqQuery->whereHas('consumable', function ($q) use ($adminLocId) {
                        $q->where('location_id', $adminLocId);
                    });
                } else {
                    // Admin Barang Umum: permintaan barang umum non-lab
                    $dedicatedLocationIds = User::where('role', 'admin')
                        ->whereNotNull('location_id')
                        ->pluck('location_id')
                        ->toArray();

                    $reqQuery->whereHas('consumable', function ($q) use ($dedicatedLocationIds) {
                        $q->whereNull('location_id')
                          ->orWhereNotIn('location_id', $dedicatedLocationIds);
                    });
                }
            }

            $pendingRequests = $reqQuery->latest()->get();
        }

        return view('consumables.index', compact('consumables', 'myRequests', 'pendingRequests'));
    }

    public function create()
    {
        $this->authorize('create', Consumable::class);

        $locations = Location::all();
        return view('consumables.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Consumable::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'location_id' => 'nullable|exists:locations,id',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('consumables/photos', 'public');
        }

        DB::transaction(function () use ($validated) {
            $consumable = Consumable::create($validated);
            
            // Record initial stock as 'in' transaction
            if ($consumable->stock > 0) {
                ConsumableTransaction::create([
                    'consumable_id' => $consumable->id,
                    'user_id' => auth()->id(),
                    'type' => 'in',
                    'quantity' => $consumable->stock,
                    'transaction_date' => now(),
                    'notes' => 'Stok awal sistem',
                ]);
            }
        });

        return redirect()->route('consumables.index')->with('success', 'Barang habis pakai berhasil ditambahkan!');
    }

    public function show(Consumable $consumable)
    {
        $transactions = $consumable->transactions()->with('user')->latest('transaction_date')->latest('id')->paginate(10);
        return view('consumables.show', compact('consumable', 'transactions'));
    }

    public function edit(Consumable $consumable)
    {
        $this->authorize('update', $consumable);

        $locations = Location::all();
        return view('consumables.edit', compact('consumable', 'locations'));
    }

    public function update(Request $request, Consumable $consumable)
    {
        $this->authorize('update', $consumable);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'location_id' => 'nullable|exists:locations,id',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($consumable->photo) {
                Storage::disk('public')->delete($consumable->photo);
            }
            $validated['photo'] = $request->file('photo')->store('consumables/photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($consumable->photo) {
                Storage::disk('public')->delete($consumable->photo);
            }
            $validated['photo'] = null;
        }

        unset($validated['remove_photo']);

        $consumable->update($validated);

        return redirect()->route('consumables.index')->with('success', 'Barang habis pakai berhasil diperbarui!');
    }

    public function destroy(Consumable $consumable)
    {
        $this->authorize('delete', $consumable);

        if ($consumable->photo) {
            Storage::disk('public')->delete($consumable->photo);
        }
        $consumable->delete();
        return redirect()->route('consumables.index')->with('success', 'Barang habis pakai berhasil dihapus!');
    }

    public function transactionForm(Consumable $consumable)
    {
        $this->authorize('transaction', $consumable);

        return view('consumables.transaction', compact('consumable'));
    }

    public function storeTransaction(Request $request, Consumable $consumable)
    {
        $this->authorize('transaction', $consumable);

        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validated['type'] == 'out' && $consumable->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi untuk dikeluarkan. Stok saat ini: ' . $consumable->stock)->withInput();
        }

        DB::transaction(function () use ($consumable, $validated) {
            ConsumableTransaction::create([
                'consumable_id' => $consumable->id,
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'],
            ]);

            if ($validated['type'] == 'in') {
                $consumable->increment('stock', $validated['quantity']);
            } else {
                $consumable->decrement('stock', $validated['quantity']);
            }
        });

        return redirect()->route('consumables.show', $consumable)->with('success', 'Transaksi stok berhasil dicatat!');
    }

    /**
     * Pengajuan Permintaan Barang Habis Pakai oleh User / Waka / Siswa / Guru
     */
    public function storeRequest(Request $request, Consumable $consumable)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'request_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ], [
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.min' => 'Jumlah barang minimal 1.',
            'request_date.required' => 'Tanggal pengajuan wajib diisi.',
        ]);

        if ($consumable->stock < $validated['quantity']) {
            return back()->with('error', "Stok barang ini tidak mencukupi (Tersedia: {$consumable->stock} {$consumable->unit}).")->withInput();
        }

        ConsumableRequest::create([
            'consumable_id' => $consumable->id,
            'user_id' => auth()->id(),
            'quantity' => $validated['quantity'],
            'request_date' => $validated['request_date'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return back()->with('success', "Pengajuan permintaan barang '{$consumable->name}' sebanyak {$validated['quantity']} {$consumable->unit} berhasil diajukan! Menunggu persetujuan pengelola.");
    }

    /**
     * Persetujuan (ACC) Pengajuan Barang Habis Pakai oleh Pengelola Ruangan / Admin
     */
    public function approveRequest(Request $request, ConsumableRequest $consumableRequest)
    {
        $this->authorize('approveRequest', $consumableRequest->consumable);

        if ($consumableRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah tidak berstatus pending.');
        }

        $consumable = $consumableRequest->consumable;

        if ($consumable->stock < $consumableRequest->quantity) {
            return back()->with('error', "Gagal menyetujui: Stok barang saat ini ({$consumable->stock}) kurang dari jumlah pengajuan ({$consumableRequest->quantity})!");
        }

        DB::transaction(function () use ($consumableRequest, $consumable, $request) {
            // 1. Kurangi stok barang
            $consumable->decrement('stock', $consumableRequest->quantity);

            // 2. Catat transaksi pengeluaran stok
            ConsumableTransaction::create([
                'consumable_id' => $consumable->id,
                'user_id' => $consumableRequest->user_id,
                'type' => 'out',
                'quantity' => $consumableRequest->quantity,
                'transaction_date' => now()->toDateString(),
                'notes' => "Pengajuan barang disetujui untuk {$consumableRequest->user->name}. " . ($consumableRequest->notes ? "Keperluan: {$consumableRequest->notes}" : ''),
            ]);

            // 3. Update status permintaan
            $consumableRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approval_date' => now(),
                'approval_notes' => $request->input('notes'),
            ]);
        });

        return back()->with('success', "Pengajuan '{$consumable->name}' berhasil disetujui (ACC) dan stok telah diperbarui.");
    }

    /**
     * Tolak Pengajuan Barang Habis Pakai
     */
    public function rejectRequest(Request $request, ConsumableRequest $consumableRequest)
    {
        $this->authorize('rejectRequest', $consumableRequest->consumable);

        if ($consumableRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah tidak berstatus pending.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'Alasan penolakan pengajuan wajib diisi.',
        ]);

        $consumableRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'approval_notes' => $request->reason,
        ]);

        return back()->with('success', "Pengajuan '{$consumableRequest->consumable->name}' telah ditolak.");
    }
}
