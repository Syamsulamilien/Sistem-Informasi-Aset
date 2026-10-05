<?php

namespace App\Policies;

use App\Models\MaintenanceRecord;
use App\Models\User;

class MaintenancePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua role bisa melihat daftar
    }

    public function view(User $user, MaintenanceRecord $maintenance): bool
    {
        return true; // Semua role bisa melihat detail
    }

    public function create(User $user): bool
    {
        // ✅ Admin, Teknisi, dan Viewer bisa create Maintenance
        return in_array($user->role, ['admin', 'teknisi', 'viewer']);
    }

    public function update(User $user, MaintenanceRecord $maintenance): bool
    {
        // ❌ Hanya Admin dan Teknisi bisa edit
        return in_array($user->role, ['admin', 'teknisi']);
    }

    public function delete(User $user, MaintenanceRecord $maintenance): bool
    {
        // ❌ Hanya Admin bisa delete
        return $user->role === 'admin';
    }

    public function complete(User $user, MaintenanceRecord $maintenance): bool
    {
        // ✅ Admin dan Teknisi bisa complete maintenance
        return in_array($user->role, ['admin', 'teknisi']);
    }

    public function markInProgress(User $user, MaintenanceRecord $maintenance): bool
    {
        // ✅ Admin dan Teknisi bisa mark in progress
        return in_array($user->role, ['admin', 'teknisi']);
    }
}