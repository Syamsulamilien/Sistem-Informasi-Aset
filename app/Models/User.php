<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
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

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class, 'technician_id');
    }
}

