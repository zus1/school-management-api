<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class GuardianPolicy
{
    public function before(User $user): ?bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        return null;
    }

    public function update(User $user, Guardian $subject): bool
    {
        return $this->hasGuardiansStudent($user, $subject);
    }

    public function retrieve(User $user, Guardian $subject): bool
    {
        return $this->hasGuardiansStudent($user, $subject);
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function delete(): bool
    {
        return false;
    }

    public function meDelete(User $user): bool
    {
        return $user->hasRole(Roles::GUARDIAN);
    }

    private function hasGuardiansStudent(User $user, Guardian $subject): bool
    {
        if(!$user instanceof Teacher || $user->hasRole(Roles::TEACHER) === false) {
            return false;
        }

        $students = $subject->students()->get();

        /** @var Student $student */
        foreach ($students as $student) {
            if($user->hasStudent($student) === true) {
                return true;
            }
        }

        return false;
    }
}
