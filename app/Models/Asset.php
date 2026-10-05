<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'asset_type_id',
        'kategori',
        'brand',
        'model',
        'serial_number',
        'description',
        'purchase_year',
        'price',
        'condition',
        'status',
        'location_id',
        'penanggung_jawab',
        'intensitas_pemakaian',
        'masa_pemakaian',
        'masa_pemakaian_satuan',
        'warranty_expiry_date',
        'photo',
        'invoice_number',
    ];

    protected $casts = [
        'warranty_expiry_date' => 'date',
        'price' => 'decimal:2',
    ];

    // ==============================
    // 🔗 RELASI DASAR
    // ==============================

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function histories()
    {
        return $this->hasMany(AssetHistory::class)->latest();
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // ==============================
    // 🧰 RELASI & METHOD MAINTENANCE
    // ==============================

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function latestMaintenance()
    {
        return $this->hasOne(MaintenanceRecord::class)->latestOfMany('schedule_date');
    }

    public function upcomingMaintenance()
    {
        return $this->hasMany(MaintenanceRecord::class)
            ->where('status', 'Scheduled')
            ->whereDate('schedule_date', '>=', now())
            ->orderBy('schedule_date');
    }

    public function completedMaintenance()
    {
        return $this->hasMany(MaintenanceRecord::class)
            ->where('status', 'Completed')
            ->orderBy('performed_date', 'desc');
    }

    // ✅ TAMBAHAN: Maintenance yang pending/scheduled
    public function pendingMaintenances()
    {
        return $this->hasMany(MaintenanceRecord::class)
            ->where('status', 'Scheduled')
            ->orderBy('schedule_date', 'asc');
    }

    // ✅ TAMBAHAN: Next maintenance terdekat
    public function nextMaintenance()
    {
        return $this->hasOne(MaintenanceRecord::class)
            ->where('status', 'Scheduled')
            ->where('schedule_date', '>=', now())
            ->orderBy('schedule_date', 'asc');
    }

    public function getTotalMaintenanceCostAttribute()
    {
        return $this->maintenanceRecords()->sum('cost');
    }

    public function hasOverdueMaintenance()
    {
        return $this->maintenanceRecords()
            ->where('status', 'Scheduled')
            ->whereDate('schedule_date', '<', now())
            ->exists();
    }

    // ==============================
    // ⚙️ FUNGSI LAIN
    // ==============================

    public static function generateAssetCode($assetTypeId)
    {
        $assetType = AssetType::findOrFail($assetTypeId);
        $prefix = $assetType->code_prefix;
        $year = date('Y');

        $lastAsset = self::where('asset_code', 'LIKE', $prefix . '-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAsset) {
            $lastNumber = intval(substr($lastAsset->asset_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $year . '-' . $newNumber;
    }

    public function getAssetPrefix()
    {
        return explode('-', $this->asset_code)[0] ?? 'AST';
    }

    public function isWarrantyExpiringSoon($months = 3)
    {
        if (!$this->warranty_expiry_date) {
            return false;
        }

        $expiryDate = $this->warranty_expiry_date;
        $thresholdDate = now()->addMonths($months);

        return $expiryDate->lte($thresholdDate) && $expiryDate->gte(now());
    }

    // ==============================
    // 💡 HELPER METHODS TAMBAHAN
    // ==============================

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $assetTypeId)
    {
        return $query->where('asset_type_id', $assetTypeId);
    }

    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }
}