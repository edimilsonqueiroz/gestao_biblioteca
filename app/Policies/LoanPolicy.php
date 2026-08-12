<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'bibliotecaria';
    }

    public function view(User $user, Loan $loan): bool
    {
        return $user->role === 'bibliotecaria' || $user->id === $loan->user_id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Loan $loan): bool
    {
        return $user->role === 'bibliotecaria';
    }

    public function delete(User $user, Loan $loan): bool
    {
        return $user->role === 'bibliotecaria' || $user->id === $loan->user_id;
    }

    public function restore(User $user, Loan $loan): bool
    {
        return $user->role === 'bibliotecaria';
    }

    public function forceDelete(User $user, Loan $loan): bool
    {
        return $user->role === 'bibliotecaria';
    }
}
