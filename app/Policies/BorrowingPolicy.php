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
        if ($user->isUser() && !$user->isPimpinan()) {
            return $borrowing->user_id === $user->id;
        }

        // Pimpinan / Waka bisa melihat semua data peminjaman untuk monitoring
        if ($user->isPimpinan()) {
            return true;
        }

        // Admin dibatasi hanya untuk aset yang berada dalam wewenangnya
        if ($user->role === 'admin') {
            return $user->managesLocation($borrowing->asset?->location_id);
        }

        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran', 'pimpinan', 'pimpinan_sekolah', 'waka', 'wakasek', 'karyawan', 'guru', 'siswa', 'user', 'user_biasa', 'siswa_guru']);
    }

    public function update(User $user, Borrowing $borrowing): bool
    {
        if ($user->role === 'admin') {
            return $user->managesLocation($borrowing->asset?->location_id);
        }

        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran']);
    }

    public function delete(User $user, Borrowing $borrowing): bool
    {
        if ($user->role === 'admin') {
            return $user->managesLocation($borrowing->asset?->location_id);
        }

        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function approve(User $user, Borrowing $borrowing): bool
    {
        return $user->managesLocation($borrowing->asset?->location_id);
    }

    public function reject(User $user, Borrowing $borrowing): bool
    {
        return $user->managesLocation($borrowing->asset?->location_id);
    }

    public function returnDirect(User $user, Borrowing $borrowing): bool
    {
        // Peminjam barang atau admin yang berwenang boleh mengembalikan barang
        if ($borrowing->user_id === $user->id) {
            return true;
        }
        return $user->managesLocation($borrowing->asset?->location_id);
    }
}
