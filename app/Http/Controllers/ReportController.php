<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetType;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Ambil data untuk filter
        $assetTypes = AssetType::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        // Ambil daftar kategori unik dari asset_types
        $categories = AssetType::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        // Ambil daftar tahun yang tersedia dari purchase_year
        $years = Asset::selectRaw('DISTINCT purchase_year')
            ->whereNotNull('purchase_year')
            ->orderBy('purchase_year', 'desc')
            ->pluck('purchase_year');

        // Statistik umum
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'Aktif')->count();
        $inactiveAssets = Asset::where('status', 'Nonaktif')->count();
        $totalValue = Asset::sum('price');

        // Statistik per jenis aset
        $assetsByType = Asset::with('assetType')
            ->selectRaw('asset_type_id, COUNT(*) as total, SUM(price) as total_price')
            ->groupBy('asset_type_id')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->assetType->name ?? 'N/A',
                    'total' => $item->total,
                    'total_price' => $item->total_price
                ];
            });

        // Statistik per deskripsi (menggantikan RAM)
        $assetsByDescription = Asset::with('assetType')
            ->whereNotNull('description')
            ->selectRaw('asset_type_id, description, COUNT(*) as total')
            ->groupBy('asset_type_id', 'description')
            ->orderBy('asset_type_id')
            ->orderBy('description')
            ->get()
            ->groupBy(function($item) {
                return $item->assetType->name ?? 'N/A';
            })
            ->map(function($items) {
                return $items->mapWithKeys(function($item) {
                    return [$item->description => $item->total];
                });
            });

        // Statistik per kondisi (gunakan backticks untuk reserved keyword)
        $assetsByCondition = Asset::selectRaw('`condition`, COUNT(*) as total')
            ->groupBy('condition')
            ->pluck('total', 'condition');

        // Statistik per lokasi
        $assetsByLocation = Asset::with('location')
            ->selectRaw('location_id, COUNT(*) as total')
            ->groupBy('location_id')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->location->name ?? 'N/A',
                    'total' => $item->total
                ];
            });

        return view('reports.index', compact(
            'assetTypes',
            'locations',
            'categories',
            'years',
            'totalAssets',
            'activeAssets',
            'inactiveAssets',
            'totalValue',
            'assetsByType',
            'assetsByDescription',
            'assetsByCondition',
            'assetsByLocation'
        ));
    }

public function exportPdf(Request $request)
{
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300);

    // Query tanpa filter untuk memastikan semua asset ter-load
    $query = Asset::with(['location:id,name', 'assetType:id,name,kategori'])
        ->select('assets.*');

    // HANYA apply filter jika ada parameter yang dikirim
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
    if ($request->filled('year')) {
        $query->where('purchase_year', $request->year);
    }
    // Filter berdasarkan kategori
    if ($request->filled('kategori')) {
        $query->whereHas('assetType', function($q) use ($request) {
            $q->where('kategori', $request->kategori);
        });
    }

    $assets = $query->orderBy('asset_code')->get();

    // DEBUG: Log query info
    \Log::info('PDF Export Query', [
        'total_assets' => $assets->count(),
        'filters' => $request->only(['asset_type_id', 'status', 'location_id', 'condition', 'year', 'kategori']),
        'latest_asset_code' => $assets->last()->asset_code ?? 'N/A'
    ]);

    // Tambahkan FILTER yang dipakai user
    $appliedFilters = [
        'asset_type' => $request->filled('asset_type_id')
            ? AssetType::find($request->asset_type_id)->name
            : null,

        'kategori' => $request->kategori ?: null,

        'status' => $request->status ?: null,

        'condition' => $request->condition ?: null,

        'location' => $request->filled('location_id')
            ? Location::find($request->location_id)->name
            : null,

        'year' => $request->year ?: null,
    ];

    // Ambil maintenance terbaru per asset
    $assetIds = $assets->pluck('id')->toArray();
    $maintenances = MaintenanceRecord::select('maintenance_records.*')
        ->whereIn('asset_id', $assetIds)
        ->whereIn('id', function($query) use ($assetIds) {
            $query->select(DB::raw('MAX(id)'))
                ->from('maintenance_records')
                ->whereIn('asset_id', $assetIds)
                ->groupBy('asset_id');
        })
        ->with('technician:id,name')
        ->get()
        ->keyBy('asset_id');

    // Statistik
    $statistics = [
        'total_assets' => $assets->count(),
        'active_count' => $assets->where('status', 'Aktif')->count(),
        'inactive_count' => $assets->where('status', 'Nonaktif')->count(),
        'total_value' => $assets->sum('price'),
        'by_type' => $assets->groupBy(fn($i) => $i->assetType->name ?? 'N/A')
            ->map(fn($items) => [
                'count' => $items->count(),
                'total_price' => $items->sum('price')
            ]),
        'by_description' => $assets->filter(fn($i) => !empty($i->description))
            ->groupBy(fn($i) => $i->assetType->name ?? 'N/A')
            ->map(fn($items) => $items->groupBy('description')
                ->map(fn($descItems) => $descItems->count())
                ->sortKeys()),
        'by_condition' => $assets->groupBy('condition')->map(fn($items) => $items->count()),
        'by_location' => $assets->groupBy(fn($i) => $i->location->name ?? 'N/A')
            ->map(fn($items) => $items->count())
    ];

    try {
        $pdf = Pdf::loadView('reports.pdf', compact('assets', 'statistics', 'maintenances', 'appliedFilters'))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'laporan-aset-' . date('Y-m-d-His') . '.pdf';

        return response()->streamDownload(
            function() use ($pdf) {
                echo $pdf->output();
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT'
            ]
        );
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
    }
}

    
    // Method baru untuk VIEW PDF di browser
 public function viewPdf(Request $request)
{
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300);

    // Query tanpa filter untuk memastikan semua asset ter-load
    $query = Asset::with(['location:id,name', 'assetType:id,name,kategori'])
        ->select('assets.*');

    // HANYA apply filter jika ada parameter yang dikirim
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
    if ($request->filled('year')) {
        $query->where('purchase_year', $request->year);
    }
    // Filter berdasarkan kategori
    if ($request->filled('kategori')) {
        $query->whereHas('assetType', function($q) use ($request) {
            $q->where('kategori', $request->kategori);
        });
    }

    $assets = $query->orderBy('asset_code')->get();

    // DEBUG: Log query info
    \Log::info('PDF View Query', [
        'total_assets' => $assets->count(),
        'filters' => $request->only(['asset_type_id', 'status', 'location_id', 'condition', 'year', 'kategori']),
        'latest_asset_code' => $assets->last()->asset_code ?? 'N/A'
    ]);

    // Tambahkan FILTER yang dipakai user
    $appliedFilters = [
        'asset_type' => $request->filled('asset_type_id')
            ? AssetType::find($request->asset_type_id)->name
            : null,

        'kategori' => $request->kategori ?: null,

        'status' => $request->status ?: null,

        'condition' => $request->condition ?: null,

        'location' => $request->filled('location_id')
            ? Location::find($request->location_id)->name
            : null,

        'year' => $request->year ?: null,
    ];

    $assetIds = $assets->pluck('id')->toArray();
    $maintenances = MaintenanceRecord::select('maintenance_records.*')
        ->whereIn('asset_id', $assetIds)
        ->whereIn('id', function($query) use ($assetIds) {
            $query->select(DB::raw('MAX(id)'))
                ->from('maintenance_records')
                ->whereIn('asset_id', $assetIds)
                ->groupBy('asset_id');
        })
        ->with('technician:id,name')
        ->get()
        ->keyBy('asset_id');

    $statistics = [
        'total_assets' => $assets->count(),
        'active_count' => $assets->where('status', 'Aktif')->count(),
        'inactive_count' => $assets->where('status', 'Nonaktif')->count(),
        'total_value' => $assets->sum('price'),
        'by_type' => $assets->groupBy(fn($i) => $i->assetType->name ?? 'N/A')
            ->map(fn($items) => [
                'count' => $items->count(),
                'total_price' => $items->sum('price')
            ]),
        'by_description' => $assets->filter(fn($i) => !empty($i->description))
            ->groupBy(fn($i) => $i->assetType->name ?? 'N/A')
            ->map(fn($items) => $items->groupBy('description')
                ->map(fn($descItems) => $descItems->count())
                ->sortKeys()),
        'by_condition' => $assets->groupBy('condition')->map(fn($items) => $items->count()),
        'by_location' => $assets->groupBy(fn($i) => $i->location->name ?? 'N/A')
            ->map(fn($items) => $items->count())
    ];

    try {
        $pdf = Pdf::loadView('reports.pdf', compact('assets', 'statistics', 'maintenances', 'appliedFilters'))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-aset-' . date('Y-m-d-His') . '.pdf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT'
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
    }
}

    
    public function exportExcel(Request $request)
    {
        // Implementasi export Excel jika diperlukan
        // Bisa menggunakan library seperti Maatwebsite\Excel
    }
}