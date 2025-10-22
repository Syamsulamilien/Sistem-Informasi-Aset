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
        return in_array($user->role, ['admin', 'teknisi']);
    }

    public function update(User $user, Asset $asset): bool
    {
        return in_array($user->role, ['admin', 'teknisi']);
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->role === 'admin';
    }
}
