<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableTransaction extends Model
{
    protected $fillable = [
        'consumable_id',
        'user_id',
        'type',
        'quantity',
        'transaction_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
