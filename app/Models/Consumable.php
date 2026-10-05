<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = [
        'name',
        'kategori',
        'sumber_dana',
        'price',
        'stock',
        'unit',
        'description',
        'location_id',
        'photo',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function transactions()
    {
        return $this->hasMany(ConsumableTransaction::class);
    }
}
