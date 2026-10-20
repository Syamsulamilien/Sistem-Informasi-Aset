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
        // ✅ Super Admin dan Admin bisa menambah Lokasi
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function update(User $user, Location $location): bool
    {
        // ✅ Super Admin dan Admin bisa edit Lokasi
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function delete(User $user, Location $location): bool
    {
        // ✅ Super Admin dan Admin bisa delete Lokasi
        return in_array($user->role, ['super_admin', 'admin']);
    }
}