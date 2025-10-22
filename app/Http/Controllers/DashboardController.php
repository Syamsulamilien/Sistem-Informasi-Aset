<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'Aktif')->count();
        $inactiveAssets = Asset::where('status', 'Nonaktif')->count();
        
        // ✅ PERBAIKAN: Aset per jenis dari tabel asset_types
        $assetsByType = AssetType::withCount('assets')
            ->having('assets_count', '>', 0)
            ->get()
            ->pluck('assets_count', 'name');
        
        // Aset per lokasi
        $assetsByLocation = Location::withCount('assets')
            ->having('assets_count', '>', 0)
            ->get();
        
        // Warranty expiring soon (30 hari - sesuai dengan card)
        $expiringWarranties = Asset::whereNotNull('warranty_expiry_date')
            ->where('warranty_expiry_date', '>=', now())
            ->where('warranty_expiry_date', '<=', now()->addDays(30))
            ->with('location')
            ->get();
        
        // Recent activities
        $recentAssets = Asset::with(['location', 'assetType'])->latest()->take(5)->get();
        
        return view('dashboard', compact(
            'totalAssets',
            'activeAssets',
            'inactiveAssets',
            'assetsByType',
            'assetsByLocation',
            'expiringWarranties',
            'recentAssets'
        ));
    }
}