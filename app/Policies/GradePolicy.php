<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\User;
use Illuminate\Http\Request;

class GradePolicy extends SchoolDirectoryPolicy
{
    public function gradeChart(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) ||
            $user->hasRole(Roles::TEACHER) ||
            $user->hasRole(Roles::STUDENT) ||
            $user->hasRole(Roles::GUARDIAN);
    }
}
