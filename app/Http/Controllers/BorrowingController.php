<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class BorrowingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Borrowing::with(['asset.location', 'user', 'approver']);

        $user = auth()->user();

        // 1. Role Scoping
        if ($user->isUser() && !$user->isPimpinan()) {
            // Siswa, Guru, Karyawan hanya melihat data peminjaman miliknya sendiri
            $query->where('user_id', $user->id);
        } elseif ($user->isPimpinan()) {
            // Pimpinan & Waka dapat memantau seluruh peminjaman sekolah
        } elseif ($user->role === 'admin') {
            if ($user->location_id) {
                // Admin Lab khusus: hanya melihat peminjaman barang ruangannya
                $adminLocId = $user->location_id;
                $query->whereHas('asset', function ($q) use ($adminLocId) {
                    $q->where('location_id', $adminLocId);
                });
            } else {
                // Admin Barang Umum: melihat aset umum (yang tidak memiliki admin lab khusus)
                $dedicatedLocationIds = \App\Models\User::where('role', 'admin')
                    ->whereNotNull('location_id')
                    ->pluck('location_id')
                    ->toArray();

                $query->whereHas('asset', function ($q) use ($dedicatedLocationIds) {
                    $q->whereNull('location_id')
                      ->orWhereNotIn('location_id', $dedicatedLocationIds);
                });
            }
        }

        // 2. Search Filter (nama barang, kode aset, merk, peminjam)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('asset', function ($qa) use ($search) {
                    $qa->where('name', 'like', "%{$search}%")
                       ->orWhere('asset_code', 'like', "%{$search}%")
                       ->orWhere('brand', 'like', "%{$search}%");
                })->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%")
                       ->orWhere('username', 'like', "%{$search}%");
                });
            });
        }

        // 3. Status Filter (pending, borrowed, returned, rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $borrowings = $query->latest()->paginate(15)->withQueryString();
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $this->authorize('create', Borrowing::class);

        // Hanya aset aktif yang TIDAK sedang dipinjam (tidak berstatus 'borrowed')
        $query = Asset::where('status', 'Aktif')
            ->whereDoesntHave('borrowings', function ($q) {
                $q->where('status', 'borrowed');
            });

        $user = auth()->user();
        if ($user->role === 'admin') {
            if ($user->location_id) {
                $query->where('location_id', $user->location_id);
            } else {
                $dedicatedLocationIds = \App\Models\User::where('role', 'admin')
                    ->whereNotNull('location_id')
                    ->pluck('location_id')
                    ->toArray();

                $query->where(function ($q) use ($dedicatedLocationIds) {
                    $q->whereNull('location_id')
                      ->orWhereNotIn('location_id', $dedicatedLocationIds);
                });
            }
        }

        $assets = $query->with('location')->orderBy('brand')->get();
        return view('borrowings.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Borrowing::class);
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
            'borrow_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'borrow_photo_base64' => 'nullable|string',
        ]);

        // Cegah barang yang sedang dipinjam untuk dipinjam kembali
        $isAlreadyBorrowed = Borrowing::where('asset_id', $validated['asset_id'])
            ->where('status', 'borrowed')
            ->exists();

        if ($isAlreadyBorrowed) {
            return back()->withInput()->withErrors([
                'asset_id' => 'Barang/aset ini sedang dalam masa peminjaman dan belum dikembalikan. Silakan pilih aset lain.'
            ]);
        }

        $user = auth()->user();
        $validated['user_id'] = $user->id;

        // User biasa (siswa/guru/karyawan) memerlukan ACC / persetujuan dari admin terkait
        $isRegularUser = $user->isUser() && !$user->isPimpinan();
        if ($isRegularUser) {
            $validated['status'] = 'pending';
            $validated['approved_by'] = null;
            $validated['approval_date'] = null;
            $successMsg = 'Pengajuan peminjaman berhasil diajukan! Menunggu persetujuan (ACC) dari pengelola ruangan/admin terkait.';
        } else {
            // Admin atau super admin mencatat langsung status 'borrowed'
            $validated['status'] = 'borrowed';
            $validated['approved_by'] = $user->id;
            $validated['approval_date'] = now();
            $successMsg = 'Peminjaman berhasil dicatat! Status: Dipinjam.';
        }

        $photoPath = $this->handleImageUpload($request, 'borrow_photo', 'borrow_photo_base64', 'borrow');
        if ($photoPath) {
            $validated['borrow_photo'] = $photoPath;
        }
        unset($validated['borrow_photo_base64']);

        Borrowing::create($validated);

        return redirect()->route('borrowings.index')->with('success', $successMsg);
    }

    public function show(Borrowing $borrowing)
    {
        $this->authorize('view', $borrowing);

        $borrowing->load(['asset.location', 'user', 'approver']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        $this->authorize('update', $borrowing);

        return view('borrowings.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $this->authorize('update', $borrowing);

        $validated = $request->validate([
            'status' => 'required|in:borrowed,returned',
            'actual_return_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'borrow_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'borrow_photo_base64' => 'nullable|string',
            'return_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'return_photo_base64' => 'nullable|string',
        ]);

        if ($validated['status'] == 'returned' && !$request->filled('actual_return_date')) {
            $validated['actual_return_date'] = now()->toDateString();
        }

        // Handle borrow photo
        if ($newBorrowPhoto = $this->handleImageUpload($request, 'borrow_photo', 'borrow_photo_base64', 'borrow')) {
            if ($borrowing->borrow_photo) {
                Storage::disk('public')->delete($borrowing->borrow_photo);
            }
            $validated['borrow_photo'] = $newBorrowPhoto;
        } elseif ($request->boolean('remove_borrow_photo')) {
            if ($borrowing->borrow_photo) {
                Storage::disk('public')->delete($borrowing->borrow_photo);
            }
            $validated['borrow_photo'] = null;
        }

        // Handle return photo
        if ($newReturnPhoto = $this->handleImageUpload($request, 'return_photo', 'return_photo_base64', 'return')) {
            if ($borrowing->return_photo) {
                Storage::disk('public')->delete($borrowing->return_photo);
            }
            $validated['return_photo'] = $newReturnPhoto;
        } elseif ($request->boolean('remove_return_photo')) {
            if ($borrowing->return_photo) {
                Storage::disk('public')->delete($borrowing->return_photo);
            }
            $validated['return_photo'] = null;
        }

        unset($validated['borrow_photo_base64'], $validated['return_photo_base64'], $validated['remove_borrow_photo'], $validated['remove_return_photo']);

        $borrowing->update($validated);

        return redirect()->route('borrowings.index')->with('success', 'Status peminjaman berhasil diperbarui!');
    }

    public function approve(Request $request, Borrowing $borrowing)
    {
        $this->authorize('approve', $borrowing);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah tidak berstatus pending.');
        }

        // Cegah jika aset sedang berstatus 'borrowed' di peminjaman aktif lainnya
        $isAlreadyBorrowed = Borrowing::where('asset_id', $borrowing->asset_id)
            ->where('status', 'borrowed')
            ->where('id', '!=', $borrowing->id)
            ->exists();

        if ($isAlreadyBorrowed) {
            return back()->with('error', 'Barang ini sedang dipinjam oleh peminjam lain dan belum dikembalikan!');
        }

        $borrowing->update([
            'status' => 'borrowed',
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'approval_notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil disetujui (ACC)! Status: Dipinjam.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $this->authorize('reject', $borrowing);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah tidak berstatus pending.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $borrowing->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'approval_notes' => $request->reason,
        ]);

        return back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    public function returnDirect(Request $request, Borrowing $borrowing)
    {
        $this->authorize('returnDirect', $borrowing);

        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Hanya peminjaman berstatus "Dipinjam" yang dapat dikembalikan.');
        }

        $request->validate([
            'actual_return_date' => 'required|date',
            'return_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'return_photo_base64' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => 'returned',
            'actual_return_date' => $request->actual_return_date,
        ];

        $photoPath = $this->handleImageUpload($request, 'return_photo', 'return_photo_base64', 'return');
        if ($photoPath) {
            $data['return_photo'] = $photoPath;
        }

        if ($request->filled('notes')) {
            $prefix = $borrowing->notes ? $borrowing->notes . "\n[Catatan Pengembalian]: " : "[Catatan Pengembalian]: ";
            $data['notes'] = $prefix . $request->notes;
        }

        $borrowing->update($data);

        return back()->with('success', 'Barang berhasil dikembalikan secara langsung!');
    }

    public function destroy(Borrowing $borrowing)
    {
        $this->authorize('delete', $borrowing);

        if ($borrowing->borrow_photo) {
            Storage::disk('public')->delete($borrowing->borrow_photo);
        }
        if ($borrowing->return_photo) {
            Storage::disk('public')->delete($borrowing->return_photo);
        }

        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman berhasil dihapus!');
    }

    /**
     * Handle file upload or base64 data url from camera
     */
    private function handleImageUpload(Request $request, string $fileKey, string $base64Key, string $prefix): ?string
    {
        if ($request->hasFile($fileKey)) {
            return $request->file($fileKey)->store('borrowings/photos', 'public');
        }

        $base64Data = $request->input($base64Key);
        if ($base64Data && preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
            $imageType = strtolower($matches[1]);
            if (in_array($imageType, ['jpeg', 'jpg', 'png', 'webp'])) {
                $data = substr($base64Data, strpos($base64Data, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $ext = ($imageType === 'jpeg') ? 'jpg' : $imageType;
                    $filename = 'borrowings/photos/' . $prefix . '_' . uniqid() . '_' . time() . '.' . $ext;
                    Storage::disk('public')->put($filename, $decoded);
                    return $filename;
                }
            }
        }

        return null;
    }
}
