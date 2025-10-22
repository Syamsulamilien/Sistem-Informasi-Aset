<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Location;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssetController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Asset::with(['location', 'assetType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('asset_type_id')) {
            $query->where('asset_type_id', $request->asset_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $assets = $query->latest()->paginate(15)->withQueryString();
        $locations = Location::all();
        $assetTypes = AssetType::active()->get();

        return view('assets.index', compact('assets', 'locations', 'assetTypes'));
    }

    public function create()
    {
        $locations = Location::all();
        $assetTypes = AssetType::active()->orderBy('name')->get();

        return view('assets.create', compact('locations', 'assetTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|unique:assets',
            'description' => 'nullable|string|max:1000',
            'purchase_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status' => 'required|in:Aktif,Nonaktif',
            'location_id' => 'required|exists:locations,id',
            'warranty_expiry_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'invoice_number' => 'nullable|string|max:255',
        ]);

        $validated['asset_code'] = Asset::generateAssetCode($validated['asset_type_id']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset = Asset::create($validated);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'Tambah Aset',
            'to_location' => $asset->location_id,
            'notes' => 'Aset baru ditambahkan',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil ditambahkan dengan kode: ' . $asset->asset_code);
    }

    public function show(Asset $asset)
    {
        $asset->load(['location', 'assetType', 'histories.user', 'maintenanceRecords.technician']);

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $locations = Location::all();
        $assetTypes = AssetType::active()->orderBy('name')->get();

        return view('assets.edit', compact('asset', 'locations', 'assetTypes'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'asset_code' => 'required|unique:assets,asset_code,' . $asset->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|unique:assets,serial_number,' . $asset->id,
            'description' => 'nullable|string|max:1000', // ✅ SUDAH DIPERBAIKI
            'purchase_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status' => 'required|in:Aktif,Nonaktif',
            'location_id' => 'required|exists:locations,id',
            'warranty_expiry_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'invoice_number' => 'nullable|string|max:255',
        ]);

        $locationChanged = $asset->location_id != $validated['location_id'];
        $oldLocationId = $asset->location_id;
        $statusChanged = $asset->status != $validated['status'];

        if ($request->hasFile('photo')) {
            if ($asset->photo) {
                Storage::disk('public')->delete($asset->photo);
            }
            $validated['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset->update($validated);

        if ($locationChanged) {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'Pindah Lokasi',
                'from_location' => $oldLocationId,
                'to_location' => $validated['location_id'],
                'notes' => 'Aset dipindahkan',
                'user_id' => auth()->id(),
            ]);
        } elseif ($statusChanged) {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'Ubah Status',
                'notes' => 'Status aset diubah menjadi ' . $validated['status'],
                'user_id' => auth()->id(),
            ]);
        } else {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'Update Data',
                'notes' => 'Data aset diperbarui',
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui!');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->photo) {
            Storage::disk('public')->delete($asset->photo);
        }

        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus!');
    }

    public function qrcode(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);

        $qrcode = QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->errorCorrection('H')
            ->generate($url);

        return view('assets.qrcode', compact('asset', 'qrcode', 'url'));
    }

    public function publicInfo($assetCode)
    {
        $asset = Asset::where('asset_code', $assetCode)
            ->with(['location', 'assetType', 'histories' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->firstOrFail();

        return view('assets.public-info', compact('asset'));
    }

    public function downloadQrCode(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);

        $qrcode = QrCode::format('png')
            ->size(500)
            ->errorCorrection('H')
            ->generate($url);

        return response($qrcode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . $asset->asset_code . '.png"');
    }

    public function printLabel(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);
        $qrcode = QrCode::size(200)->generate($url);

        return view('assets.print-label', compact('asset', 'qrcode'));
    }

    public function generateCode(Request $request)
    {
        $assetTypeId = $request->input('asset_type_id');

        if (!$assetTypeId) {
            return response()->json(['error' => 'Asset type required'], 400);
        }

        try {
            $assetCode = Asset::generateAssetCode($assetTypeId);
            return response()->json(['asset_code' => $assetCode]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}