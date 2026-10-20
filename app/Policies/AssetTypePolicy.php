<?php

namespace App\Policies;

use App\Models\AssetType;
use App\Models\User;

class AssetTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AssetType $assetType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function update(User $user, AssetType $assetType): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function delete(User $user, AssetType $assetType): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }
}
