<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;

class BorrowingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Borrowing $borrowing): bool
    {
        // Siswa/Guru/Karyawan hanya bisa melihat data peminjaman miliknya sendiri
        if ($user->isUser()) {
            return $borrowing->user_id === $user->id;
        }

        // Admin dibatasi hanya untuk aset yang berada di ruangannya
        if ($user->role === 'admin' && $user->location_id) {
            return $borrowing->asset && $borrowing->asset->location_id == $user->location_id;
        }

        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran', 'pimpinan', 'pimpinan_sekolah', 'karyawan', 'guru', 'siswa', 'user', 'user_biasa', 'siswa_guru']);
    }

    public function update(User $user, Borrowing $borrowing): bool
    {
        // Admin hanya bisa update peminjaman barang ruangannya
        if ($user->role === 'admin' && $user->location_id) {
            return $borrowing->asset && $borrowing->asset->location_id == $user->location_id;
        }

        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran']);
    }

    public function delete(User $user, Borrowing $borrowing): bool
    {
        // Admin hanya bisa hapus riwayat peminjaman barang ruangannya
        if ($user->role === 'admin' && $user->location_id) {
            return $borrowing->asset && $borrowing->asset->location_id == $user->location_id;
        }

        return in_array($user->role, ['super_admin', 'admin']);
    }
}
