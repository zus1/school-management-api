<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\User;

class ProductPolicy
{
    public function before(User $user): ?bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        return null;
    }

    public function retrieve(User $user): bool
    {
        return $user->hasRole(Roles::GUARDIAN);
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::GUARDIAN);
    }
}
