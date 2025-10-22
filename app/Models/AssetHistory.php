<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'action',
        'from_location',
        'to_location',
        'notes',
        'user_id',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function fromLocationData()
    {
        return $this->belongsTo(Location::class, 'from_location');
    }

    public function toLocationData()
    {
        return $this->belongsTo(Location::class, 'to_location');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}