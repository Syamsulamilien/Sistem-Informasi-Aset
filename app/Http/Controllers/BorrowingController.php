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
        $query = Borrowing::with(['asset.location', 'user']);

        // Jika user adalah siswa, guru, karyawan, atau viewer: hanya tampilkan miliknya sendiri
        if (auth()->user()->isUser() || auth()->user()->isUserBiasa()) {
            $query->where('user_id', auth()->id());
        } elseif (auth()->user()->role === 'admin' && auth()->user()->location_id) {
            // Jika Admin terikat ke ruangan/lab tertentu, hanya tampilkan peminjaman barang ruangannya
            $adminLocId = auth()->user()->location_id;
            $query->whereHas('asset', function ($q) use ($adminLocId) {
                $q->where('location_id', $adminLocId);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        if (auth()->user()->role === 'admin' && auth()->user()->location_id) {
            $query->where('location_id', auth()->user()->location_id);
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

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'borrowed'; // Langsung berstatus Dipinjam (tidak ada lagi pending)

        $photoPath = $this->handleImageUpload($request, 'borrow_photo', 'borrow_photo_base64', 'borrow');
        if ($photoPath) {
            $validated['borrow_photo'] = $photoPath;
        }
        unset($validated['borrow_photo_base64']);

        Borrowing::create($validated);

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat! Status: Dipinjam.');
    }

    public function show(Borrowing $borrowing)
    {
        $this->authorize('view', $borrowing);

        $borrowing->load(['asset.location', 'user']);
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
