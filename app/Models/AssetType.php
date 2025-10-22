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
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}
