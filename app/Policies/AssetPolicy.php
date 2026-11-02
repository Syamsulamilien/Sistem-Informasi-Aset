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
        // Jika Admin memiliki ruangan tertentu, hanya bisa melihat detail aset ruangannya
        if ($user->role === 'admin' && $user->location_id) {
            return $asset->location_id == $user->location_id;
        }

        return true; // Super Admin, Pimpinan, dan User lain
    }

    public function create(User $user): bool
    {
        // Super Admin dan Admin bisa menambah Master Aset
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function update(User $user, Asset $asset): bool
    {
        // Admin dibatasi hanya untuk ruangan yang ditugaskan
        if ($user->role === 'admin' && $user->location_id) {
            return $asset->location_id == $user->location_id;
        }

        return in_array($user->role, ['super_admin', 'admin', 'teknisi']);
    }

    public function delete(User $user, Asset $asset): bool
    {
        // Admin dibatasi hanya untuk ruangan yang ditugaskan
        if ($user->role === 'admin' && $user->location_id) {
            return $asset->location_id == $user->location_id;
        }

        return in_array($user->role, ['super_admin', 'admin']);
    }
}