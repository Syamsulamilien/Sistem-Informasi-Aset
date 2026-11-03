<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role', // role selalu di-set eksplisit via array di controller, bukan mass assignment
        'gender', // 'Laki-laki' atau 'Perempuan' untuk semua user
        'class_name', // Kelas (khusus role siswa, misal 'X Farmasi 1')
        'location_id', // penugasan ruangan/lab khusus untuk role admin
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

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isPimpinan()
    {
        return in_array($this->role, ['pimpinan', 'pimpinan_sekolah']);
    }

    public function isUser()
    {
        return in_array($this->role, ['user', 'siswa', 'guru', 'karyawan', 'user_biasa', 'siswa_guru', 'viewer']);
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }

    public function isLaboran()
    {
        return $this->role === 'laboran';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    public function isUserBiasa()
    {
        return $this->isUser();
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

