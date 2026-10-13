<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;

class BorrowingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Borrowing $borrowing): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teknisi', 'laboran']);
    }

    public function update(User $user, Borrowing $borrowing): bool
    {
        return in_array($user->role, ['admin', 'teknisi', 'laboran']);
    }

    public function delete(User $user, Borrowing $borrowing): bool
    {
        return $user->role === 'admin';
    }
}
