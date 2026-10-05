<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Location;
use App\Models\AssetType;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

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

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $assets = $query->latest()->paginate(15)->withQueryString();
        $locations = Location::all();
        $assetTypes = AssetType::active()->get();

        // Ambil daftar kategori unik dari asset_types
        $categories = AssetType::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('assets.index', compact('assets', 'locations', 'assetTypes', 'categories'));
    }

    public function create()
    {
        $locations = Location::all();
        $assetTypes = AssetType::active()->orderBy('name')->get();

        return view('assets.create', compact('locations', 'assetTypes'));
    }

// Update method store() di AssetController.php
// Ganti bagian validasi maintenance dengan ini:

public function store(Request $request)
{
    $validated = $request->validate([
        'asset_type_id' => 'required|exists:asset_types,id',
        'kategori' => 'required|string',
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'purchase_year' => [
            'required',
            'numeric',
            function ($attribute, $value, $fail) {
                $value = (int) $value;
                if ($value !== 0 && ($value < 1900 || $value > (date('Y') + 1))) {
                    $fail('Tahun pembelian harus 0 (tidak diketahui) atau antara 1900 sampai ' . (date('Y') + 1));
                }
            }
        ],
        'price' => 'nullable|numeric|min:0',
        'sumber_dana' => 'nullable|string|max:255',
        'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
        'status' => 'required|in:Aktif,Nonaktif',
        'location_id' => 'required|exists:locations,id',
        'penanggung_jawab' => 'nullable|string|max:255',
        'intensitas_pemakaian' => 'nullable|string|max:255',
        'masa_pemakaian' => 'nullable|integer|min:0',
        'masa_pemakaian_satuan' => 'nullable|in:Bulan,Tahun',
        'warranty_expiry_date' => 'nullable|date',
        'photo' => 'nullable|image|max:2048',
        'invoice_number' => 'nullable|string|max:255',

        // ✅ UPDATE: Validasi Maintenance dengan teknisi manual (Semua Optional)
        'enable_maintenance' => 'nullable|boolean',
        'maintenance_start_from' => 'nullable|in:next_month,this_month,custom',
        'maintenance_custom_date' => 'nullable|date',
        'maintenance_interval' => 'nullable|integer|in:3,6,12,24',
        'technician_type' => 'nullable|in:existing,manual',
        'maintenance_technician_id' => 'nullable|exists:users,id',
        'maintenance_technician_name' => 'nullable|string|max:255',
    ]);

    $validated['purchase_year'] = (int) $validated['purchase_year'];
    $validated['serial_number'] = $validated['serial_number'] ?? null;// ✅ FIX: Default ke string kosong
    $validated['asset_code'] = Asset::generateAssetCode($validated['asset_type_id']);

    try {
        DB::beginTransaction();

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

        // Generate Maintenance Schedules
        $maintenanceCount = 0;
        if ($request->has('enable_maintenance') && $request->enable_maintenance) {
            $maintenanceCount = $this->createMaintenanceSchedules($asset, $request);
        }

        DB::commit();

        $successMessage = 'Aset berhasil ditambahkan dengan kode: ' . $asset->asset_code;
        if ($maintenanceCount > 0) {
            $successMessage .= " dan {$maintenanceCount} jadwal maintenance otomatis telah dibuat (untuk 10 tahun ke depan).";
        }

        return redirect()->route('assets.index')->with('success', $successMessage);

    } catch (\Exception $e) {
        DB::rollBack();
        
        if (isset($validated['photo'])) {
            Storage::disk('public')->delete($validated['photo']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menambahkan aset: ' . $e->getMessage());
    }
}

/**
 * ✅ UPDATED: Generate maintenance schedules dengan support teknisi manual
 */
private function createMaintenanceSchedules(Asset $asset, Request $request)
{
    $startDate = $this->getMaintenanceStartDate(
        $request->maintenance_start_from,
        $request->maintenance_custom_date
    );

    $interval = (int) $request->input('maintenance_interval', 6);
    
    // ✅ Handle teknisi: bisa dari user_id atau nama manual
    $technicianId = null;
    $technicianName = null;
    
    if ($request->technician_type === 'manual') {
        $technicianName = $request->maintenance_technician_name;
    } else {
        $technicianId = $request->maintenance_technician_id;
    }

    $durationYears = 10;
    $totalMonths = $durationYears * 12;
    $count = (int) floor($totalMonths / $interval);

    $schedules = [];
    $currentDate = $startDate->copy();

    for ($i = 0; $i < $count; $i++) {
        $schedules[] = [
            'asset_id' => $asset->id,
            'schedule_date' => $currentDate->format('Y-m-d'),
            'status' => 'Scheduled',
            'technician_id' => $technicianId ?? 0, // Bisa null jika manual
            'technician_name' => $technicianName, // ✅ Tambahkan ini
            'notes' => sprintf(
                'Auto-generated preventive maintenance #%d (setiap %d bulan)',
                $i + 1,
                $interval
            ),
            'cost' => 0,
            'performed_date' => null,
            'tanggal_penerimaan_barang' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $currentDate->addMonths($interval);
    }

    MaintenanceRecord::insert($schedules);

    return count($schedules);
}

    /**
     * Tentukan tanggal mulai maintenance
     */
    private function getMaintenanceStartDate($startFrom, $customDate = null)
    {
        switch ($startFrom) {
            case 'this_month':
                return Carbon::now()->startOfMonth();
                
            case 'custom':
                return Carbon::parse($customDate);
                
            case 'next_month':
            default:
                return Carbon::now()->addMonth()->startOfMonth();
        }
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
            'kategori' => 'required|string',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'purchase_year' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $value = (int) $value;
                    if ($value !== 0 && ($value < 1900 || $value > (date('Y') + 1))) {
                        $fail('Tahun pembelian harus 0 (tidak diketahui) atau antara 1900 sampai ' . (date('Y') + 1));
                    }
                }
            ],
            'price' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status' => 'required|in:Aktif,Nonaktif',
            'location_id' => 'required|exists:locations,id',
            'penanggung_jawab' => 'nullable|string|max:255',
            'intensitas_pemakaian' => 'nullable|string|max:255',
            'masa_pemakaian' => 'nullable|integer|min:0',
            'masa_pemakaian_satuan' => 'nullable|in:Bulan,Tahun',
            'warranty_expiry_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'invoice_number' => 'nullable|string|max:255',
        ]);

        $validated['purchase_year'] = (int) $validated['purchase_year'];

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
            ->with([
                'location', 
                'assetType', 
                'histories' => function ($query) {
                    $query->latest()->limit(5);
                },
                'maintenanceRecords' => function ($query) {
                    $query->latest()->limit(5);
                },
                'maintenanceRecords.technician'
            ])
            ->firstOrFail();

        return view('assets.public-info', compact('asset'));
    }

    public function downloadQrCode(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);

        $qrcode = QrCode::format('svg')
            ->size(500)
            ->errorCorrection('H')
            ->generate($url);

        return response($qrcode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . $asset->asset_code . '.svg"');
    }

    public function printLabel(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);
        $qrcode = QrCode::size(200)->generate($url);

        return view('assets.print-label', compact('asset', 'qrcode'));
    }

    public function downloadLabelPdf(Asset $asset)
    {
        $url = route('assets.public-info', $asset->asset_code);
        
        // Generate QR code as base64 svg to embed in PDF
        $qrcodeImage = QrCode::format('svg')->size(150)->margin(0)->generate($url);
        $qrcodeBase64 = base64_encode($qrcodeImage);

        // Ukuran 80x30 mm dalam point (1 mm = 2.83465 pt)
        // 80 mm = 226.77 pt
        // 30 mm = 85.04 pt
        $customPaper = array(0, 0, 226.77, 85.04);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('assets.label-pdf', compact('asset', 'qrcodeBase64'))
                ->setPaper($customPaper, 'landscape');

        return $pdf->download('Label_Aset_' . $asset->asset_code . '.pdf');
    }

    public function generateCode(Request $request)
    {
        $assetTypeId = $request->input('asset_type_id');

        if (!$assetTypeId) {
            return response()->json(['error' => 'Asset type required'], 400);
        }

        try {
            $assetType = AssetType::findOrFail($assetTypeId);
            $assetCode = Asset::generateAssetCode($assetTypeId);
            
            return response()->json([
                'asset_code' => $assetCode,
                'kategori' => $assetType->kategori
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}