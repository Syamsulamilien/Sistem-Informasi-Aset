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
        return $user->role === 'admin';
    }

    public function update(User $user, AssetType $assetType): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, AssetType $assetType): bool
    {
        return $user->role === 'admin';
    }
}
