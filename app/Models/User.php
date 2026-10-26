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
        'role', // role selalu di-set eksplisit via array di controller, bukan mass assignment
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

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isUser()
    {
        return in_array($this->role, ['guru', 'siswa', 'user', 'user_biasa', 'siswa_guru']);
    }

    public function isUserBiasa()
    {
        return in_array($this->role, ['viewer', 'guru', 'siswa', 'user', 'user_biasa', 'siswa_guru']);
    }

    public function isLaboran()
    {
        return $this->role === 'laboran';
    }

    public function isPimpinan()
    {
        return in_array($this->role, ['pimpinan', 'pimpinan_sekolah']);
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

