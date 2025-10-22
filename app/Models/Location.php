<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',        // Nama Unit
        'floor',       // Lantai
        'description', // Deskripsi
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    // ========== STATISTIK METHODS ==========

    /**
     * Total jumlah aset di lokasi ini
     */
    public function getTotalAssetsAttribute()
    {
        return $this->assets()->count();
    }

    /**
     * Total nilai aset di lokasi ini
     */
    public function getTotalAssetValueAttribute()
    {
        return $this->assets()->sum('price') ?? 0;
    }

    /**
     * Total nilai aset dalam format Rupiah
     */
    public function getFormattedTotalValueAttribute()
    {
        return 'Rp ' . number_format($this->total_asset_value, 0, ',', '.');
    }

    /**
     * Jumlah aset aktif di lokasi ini
     */
    public function getActiveAssetsCountAttribute()
    {
        return $this->assets()->where('status', 'Aktif')->count();
    }

    /**
     * Jumlah aset nonaktif di lokasi ini
     */
    public function getInactiveAssetsCountAttribute()
    {
        return $this->assets()->where('status', 'Nonaktif')->count();
    }

    /**
     * Statistik aset berdasarkan tipe
     * Return: Collection dengan format [asset_type_name => count]
     */
    public function getAssetsByType()
    {
        return $this->assets()
            ->select('asset_type_id', DB::raw('count(*) as total'))
            ->with('assetType')
            ->groupBy('asset_type_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->assetType->name ?? 'Unknown' => $item->total
                ];
            });
    }

    /**
     * Statistik aset berdasarkan deskripsi (pengganti RAM)
     * Return: Collection dengan format [description => count]
     */
    public function getAssetsByDescription()
    {
        return $this->assets()
            ->select('description', DB::raw('count(*) as total'))
            ->groupBy('description')
            ->orderBy('description')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    ($item->description ?: 'Tanpa Deskripsi') => $item->total
                ];
            });
    }

    /**
     * Statistik aset berdasarkan kondisi
     * Return: Collection dengan format [condition => count]
     */
    public function getAssetsByCondition()
    {
        return $this->assets()
            ->select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->condition => $item->total
                ];
            });
    }

    /**
     * Statistik aset berdasarkan status
     * Return: Collection dengan format [status => count]
     */
    public function getAssetsByStatus()
    {
        return $this->assets()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->status => $item->total
                ];
            });
    }

    /**
     * Aset dengan warranty yang akan segera habis di lokasi ini
     */
    public function getAssetsWithExpiringSoonWarranty($months = 3)
    {
        return $this->assets()
            ->whereNotNull('warranty_expiry_date')
            ->whereBetween('warranty_expiry_date', [
                now(),
                now()->addMonths($months)
            ])
            ->get();
    }

    /**
     * Statistik lengkap untuk ditampilkan di view
     */
    public function getDetailedStatistics()
    {
        return [
            'total_assets' => $this->total_assets,
            'total_value' => $this->total_asset_value,
            'formatted_total_value' => $this->formatted_total_value,
            'active_count' => $this->active_assets_count,
            'inactive_count' => $this->inactive_assets_count,
            'by_type' => $this->getAssetsByType(),
            'by_description' => $this->getAssetsByDescription(),
            'by_condition' => $this->getAssetsByCondition(),
            'by_status' => $this->getAssetsByStatus(),
        ];
    }
}