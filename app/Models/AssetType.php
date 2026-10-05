<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_prefix',
        'kategori',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Konstanta untuk kategori
    const KATEGORI_OPTIONS = [
        'Asset TI' => 'Asset TI',
        'Asset Rumah Tangga' => 'Asset Rumah Tangga',
        'Asset Transportasi' => 'Asset Transportasi',
        'Asset Gizi' => 'Asset Gizi',
        'Asset Lainnya' => 'Asset Lainnya',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    // Helper: Format nama untuk display
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->code_prefix . ')';
    }

    // Scope: Hanya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Filter berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }
}