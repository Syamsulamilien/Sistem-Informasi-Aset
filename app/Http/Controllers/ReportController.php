<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetType;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        // Ambil data untuk filter
        $assetTypes = AssetType::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        
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
        // Query dengan relasi
        $query = Asset::with(['location', 'assetType']);
        
        // Apply filters
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
        
        $assets = $query->get();
        
        // Ambil maintenance records untuk asset-asset tersebut
        $assetIds = $assets->pluck('id')->toArray();
        $maintenances = MaintenanceRecord::with(['asset', 'technician'])
            ->whereIn('asset_id', $assetIds)
            ->orderBy('schedule_date', 'desc')
            ->get();
        
        // Statistik untuk PDF
        $statistics = [
            'total_assets' => $assets->count(),
            'active_count' => $assets->where('status', 'Aktif')->count(),
            'inactive_count' => $assets->where('status', 'Nonaktif')->count(),
            'total_value' => $assets->sum('price'),
            
            // Group by type
            'by_type' => $assets->groupBy(function($item) {
                return $item->assetType->name ?? 'N/A';
            })->map(function($items) {
                return [
                    'count' => $items->count(),
                    'total_price' => $items->sum('price')
                ];
            }),
            
            // Group by description (menggantikan RAM)
            'by_description' => $assets->filter(function($item) {
                return !empty($item->description);
            })->groupBy(function($item) {
                return $item->assetType->name ?? 'N/A';
            })->map(function($items) {
                return $items->groupBy('description')->map(function($descItems) {
                    return $descItems->count();
                })->sortKeys();
            }),
            
            // Group by condition (gunakan backticks)
            'by_condition' => $assets->groupBy('condition')->map(function($items) {
                return $items->count();
            }),
            
            // Group by location
            'by_location' => $assets->groupBy(function($item) {
                return $item->location->name ?? 'N/A';
            })->map(function($items) {
                return $items->count();
            })
        ];
        
        $pdf = Pdf::loadView('reports.pdf', compact('assets', 'statistics', 'maintenances'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-aset-' . date('Y-m-d') . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        // Implementasi export Excel jika diperlukan
        // Bisa menggunakan library seperti Maatwebsite\Excel
    }
}