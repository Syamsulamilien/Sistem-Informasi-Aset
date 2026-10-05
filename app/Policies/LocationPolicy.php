<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua role bisa melihat daftar
    }

    public function view(User $user, Location $location): bool
    {
        return true; // Semua role bisa melihat detail
    }

    public function create(User $user): bool
    {
        // ✅ Admin dan Viewer bisa create Location
        return in_array($user->role, ['admin', 'viewer']);
    }

    public function update(User $user, Location $location): bool
    {
        // ❌ Hanya Admin bisa edit
        return $user->role === 'admin';
    }

    public function delete(User $user, Location $location): bool
    {
        // ❌ Hanya Admin bisa delete
        return $user->role === 'admin';
    }
}