<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\User;

class TeacherPolicy
{
    public function pass(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN);
    }
}
