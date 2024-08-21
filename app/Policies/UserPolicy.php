<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class UserPolicy
{
    public function toggleActive(User $user, User $subject): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }
        if($user->hasRole(Roles::STUDENT) || $user->hasRole(Roles::GUARDIAN)) {
            return false;
        }

        return $this->handelTeachersPermissions($user, $subject);
    }

    private function handelTeachersPermissions(User $user, User $subject): bool
    {
        if(!$user instanceof Teacher || $subject->hasRole(Roles::TEACHER) === true) {
            return false;
        }

        if(($allowed = $this->isTeacherAllowedToHandleStudent($user, $subject)) !== null) {
            return $allowed;
        }

        if(($allowed = $this->isTeacherAllowedToHandleGuardian($user, $subject)) !== null) {
            return $allowed;
        }

        return false;
    }

    private function isTeacherAllowedToHandleStudent(Teacher $user, User $subject): ?bool
    {
        if($subject instanceof Student && $subject->hasRole(Roles::STUDENT)) {
            return $user->hasStudent($subject);
        }

        return null;
    }

    private function isTeacherAllowedToHandleGuardian(Teacher $user, User $subject): ?bool
    {
        if($subject instanceof Guardian && $subject->hasRole(Roles::GUARDIAN)) {
            $students = $subject->students()->get();

            /** @var Student $student */
            foreach ($students as $student) {
                if($user->hasStudent($student)) {
                    return true;
                }
            }

            return false;
        }

        return null;
    }
}
