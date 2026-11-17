<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableRequest extends Model
{
    protected $fillable = [
        'consumable_id',
        'user_id',
        'quantity',
        'request_date',
        'notes',
        'status',
        'approved_by',
        'approval_date',
        'approval_notes',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'approval_date' => 'datetime',
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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
