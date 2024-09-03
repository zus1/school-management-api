<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\User;

class GradePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function update(User $user, Grade $grade): bool
    {
        return $this->isAdminOrOwner($user, $grade);
    }

    public function delete(User $user, Grade $grade): bool
    {
        return $this->isAdminOrOwner($user, $grade);
    }

    public function collection(): bool
    {
        return true;
    }

    private function isAdminOrOwner(User $user, Grade $grade): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        return $user instanceof Teacher && $user->hasRole(Roles::TEACHER) && $user->id === $grade->teacher_id;

    }
}
