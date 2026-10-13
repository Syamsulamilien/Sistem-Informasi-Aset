<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua role bisa melihat daftar
    }

    public function view(User $user, Asset $asset): bool
    {
        return true; // Semua role bisa melihat detail
    }

    public function create(User $user): bool
    {
        // ❌ Hanya Admin yang bisa menambah Master Aset
        return $user->role === 'admin';
    }

    public function update(User $user, Asset $asset): bool
    {
        // ✅ Admin dan Teknisi bisa update data/kondisi
        return in_array($user->role, ['admin', 'teknisi']);
    }

    public function delete(User $user, Asset $asset): bool
    {
        // ❌ Hanya Admin yang bisa menghapus Aset
        return $user->role === 'admin';
    }
}