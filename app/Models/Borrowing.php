<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'asset_id',
        'borrow_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'expected_return_date' => 'date',
            'actual_return_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
