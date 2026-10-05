<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    public function isUserBiasa()
    {
        return in_array($this->role, ['viewer', 'user_biasa']);
    }

    public function isLaboran()
    {
        return $this->role === 'laboran';
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class, 'technician_id');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function consumableTransactions()
    {
        return $this->hasMany(ConsumableTransaction::class);
    }
}

