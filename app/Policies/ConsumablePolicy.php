<?php

namespace App\Policies;

use App\Models\Consumable;
use App\Models\User;

class ConsumablePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Consumable $consumable): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran']);
    }

    public function update(User $user, Consumable $consumable): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran']);
    }

    public function transaction(User $user, Consumable $consumable): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'teknisi', 'laboran']);
    }

    public function delete(User $user, Consumable $consumable): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }
}
