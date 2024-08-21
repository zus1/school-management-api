<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class StudentPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if($ability === 'create') {
            return null;
        }

        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function update(User $user, Student $subject): bool
    {
        return $this->hasStudent($user, $subject);
    }

    public function delete(User $user, Student $subject)
    {
        return $this->hasStudent($user, $subject);
    }

    public function retrieve(User $user, Student $subject)
    {
        if($user instanceof Teacher && $user->hasRole(Roles::TEACHER) === true) {
            return $user->hasStudent($subject);
        }

        if($user instanceof Guardian && $user->hasRole(Roles::GUARDIAN)) {
            return $user->id === $subject->guardian_id;
        }

        return false;
    }

    public function collection(User $user): bool
    {
        return $user->hasOneOfRoles([Roles::TEACHER, Roles::GUARDIAN]);
    }

    private function hasStudent(User $user, Student $subject)
    {
        if(!$user instanceof Teacher || $user->hasRole(Roles::TEACHER) === false) {
            return false;
        }

        return $user->hasStudent($subject);
    }
}
