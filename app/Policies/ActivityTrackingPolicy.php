<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\User;

class ActivityTrackingPolicy
{
    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) || $user->hasRole(Roles::TEACHER);
    }
}
