<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ConsumableController extends Controller
{
    public function index(Request $request)
    {
        $query = Consumable::with('location');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('kategori', 'like', "%{$request->search}%");
        }

        $consumables = $query->latest()->paginate(15);
        return view('consumables.index', compact('consumables'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('consumables.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'location_id' => 'nullable|exists:locations,id',
            'photo' => 'nullable|image|max:2048',
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
        $locations = Location::all();
        return view('consumables.edit', compact('consumable', 'locations'));
    }

    public function update(Request $request, Consumable $consumable)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'location_id' => 'nullable|exists:locations,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($consumable->photo) {
                Storage::disk('public')->delete($consumable->photo);
            }
            $validated['photo'] = $request->file('photo')->store('consumables/photos', 'public');
        }

        $consumable->update($validated);

        return redirect()->route('consumables.index')->with('success', 'Barang habis pakai berhasil diperbarui!');
    }

    public function destroy(Consumable $consumable)
    {
        if ($consumable->photo) {
            Storage::disk('public')->delete($consumable->photo);
        }
        $consumable->delete();
        return redirect()->route('consumables.index')->with('success', 'Barang habis pakai berhasil dihapus!');
    }

    public function transactionForm(Consumable $consumable)
    {
        return view('consumables.transaction', compact('consumable'));
    }

    public function storeTransaction(Request $request, Consumable $consumable)
    {
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
}
