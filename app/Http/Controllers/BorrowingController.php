<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Asset;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['asset', 'user']);

        // Jika user bukan admin/laboran, hanya tampilkan miliknya
        if (auth()->user()->isUserBiasa()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(15);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        // Hanya aset aktif yang bisa dipinjam
        $assets = Asset::where('status', 'Aktif')->get();
        return view('borrowings.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Borrowing::create($validated);

        return redirect()->route('borrowings.index')->with('success', 'Permohonan peminjaman berhasil dibuat, menunggu persetujuan.');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['asset', 'user']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        if (auth()->user()->isUserBiasa()) {
            abort(403);
        }
        return view('borrowings.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        if (auth()->user()->isUserBiasa()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,borrowed,returned',
            'actual_return_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] == 'returned' && !$borrowing->actual_return_date) {
            $validated['actual_return_date'] = now();
        }

        $borrowing->update($validated);

        return redirect()->route('borrowings.index')->with('success', 'Status peminjaman berhasil diupdate!');
    }

    public function destroy(Borrowing $borrowing)
    {
        if (auth()->user()->isUserBiasa()) {
            abort(403);
        }

        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman berhasil dihapus!');
    }
}
