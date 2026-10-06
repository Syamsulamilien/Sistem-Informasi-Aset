<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\Consumable;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== Kartu statistik =====
        $totalAssets    = Asset::count();
        $activeAssets   = Asset::where('status', 'Aktif')->count();
        $inactiveAssets = Asset::where('status', 'Nonaktif')->count();

        // Garansi yang sudah lewat
        $expiredWarranty = Asset::whereNotNull('warranty_expiry_date')
            ->whereDate('warranty_expiry_date', '<', now())
            ->count();

        $borrowedCount = Borrowing::where('status', 'borrowed')->count();

        // ===== Donut: barang dipinjam per jenis aset =====
        $borrowedByType = Borrowing::where('borrowings.status', 'borrowed')
            ->join('assets', 'assets.id', '=', 'borrowings.asset_id')
            ->join('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
            ->selectRaw('asset_types.name as label, COUNT(*) as total')
            ->groupBy('asset_types.name')
            ->orderByDesc('total')
            ->get();

        // ===== Aset terbaru =====
        $recentAssets = Asset::with(['location', 'assetType'])->latest()->take(3)->get();

        // ===== Garansi berakhir (30 hari ke depan) =====
        $expiringAssets = Asset::whereNotNull('warranty_expiry_date')
            ->where('warranty_expiry_date', '>=', now()->startOfDay())
            ->where('warranty_expiry_date', '<=', now()->addDays(30))
            ->with('location')
            ->orderBy('warranty_expiry_date')
            ->take(5)
            ->get();

        // ===== Bar chart: aset per lokasi (top 5) =====
        $locations = Location::withCount('assets')
            ->having('assets_count', '>', 0)
            ->orderByDesc('assets_count')
            ->take(5)
            ->get();

        // ===== Stok barang habis pakai =====
        // Batas stok "menipis" (samakan dengan halaman daftar barang habis pakai)
        $lowStockLimit = 10;

        // 5 barang dengan stok paling sedikit (yang habis/menipis tampil paling atas)
        $consumables = Consumable::with('location')
            ->orderBy('stock')
            ->orderBy('name')
            ->take(5)
            ->get();

        $totalConsumables = Consumable::count();
        $emptyCount       = Consumable::where('stock', '<=', 0)->count();
        $lowCount         = Consumable::where('stock', '>', 0)->where('stock', '<=', $lowStockLimit)->count();

        return view('dashboard', [
            'totalAssets'      => $totalAssets,
            'activeAssets'     => $activeAssets,
            'inactiveAssets'   => $inactiveAssets,
            'expiredWarranty'  => $expiredWarranty,
            'borrowedCount'    => $borrowedCount,
            'borrowedByType'   => $borrowedByType,
            'recentAssets'     => $recentAssets,
            'expiringAssets'   => $expiringAssets,
            'locationLabels'   => $locations->pluck('name'),
            'locationTotals'   => $locations->pluck('assets_count'),
            'consumables'      => $consumables,
            'totalConsumables' => $totalConsumables,
            'emptyCount'       => $emptyCount,
            'lowCount'         => $lowCount,
            'lowStockLimit'    => $lowStockLimit,
        ]);
    }
}