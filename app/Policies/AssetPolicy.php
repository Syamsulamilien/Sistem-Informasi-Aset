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
        // ✅ Super Admin dan Admin bisa menambah Master Aset
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function update(User $user, Asset $asset): bool
    {
        // ✅ Super Admin, Admin, dan Teknisi bisa update data/kondisi
        return in_array($user->role, ['super_admin', 'admin', 'teknisi']);
    }

    public function delete(User $user, Asset $asset): bool
    {
        // ✅ Super Admin dan Admin yang bisa menghapus Aset
        return in_array($user->role, ['super_admin', 'admin']);
    }
}