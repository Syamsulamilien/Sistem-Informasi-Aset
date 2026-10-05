<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'schedule_date',
        'performed_date',
        'tanggal_penerimaan_barang',
        'technician_id',
        'technician_name',  // ✅ TAMBAHKAN INI
        'notes',
        'cost',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'performed_date' => 'date',
        'tanggal_penerimaan_barang' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    
    // ✅ Accessor untuk display nama teknisi
    public function getTechnicianDisplayNameAttribute()
    {
        if ($this->technician_id && $this->technician) {
            return $this->technician->name;
        }
        
        if ($this->technician_name) {
            return $this->technician_name;
        }
        
        return 'Belum ditentukan';
    }
}