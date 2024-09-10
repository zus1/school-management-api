<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Exam;
use App\Models\GradingRule;
use App\Models\Teacher;
use App\Models\User;

class ExamPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function update(User $user, Exam $exam): bool
    {
        return $this->isAdminOrOwner($user, $exam);
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }

    public function retrieve(User $user, Exam $exam): bool
    {
        return $this->isAdminOrOwner($user, $exam);
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER) || $user->hasRole(Roles::ADMIN);
    }

    public function toggleGradingRule(User $user, Exam $exam, ?GradingRule $gradingRule = null): bool
    {
        $isAdmin = $user->hasRole(Roles::ADMIN);
        $isTeacher = $user->hasRole(Roles::TEACHER);
        $examBelongsToTeacher = $user->id === $exam->teacher_id;

        if($gradingRule !== null) {
            return $isAdmin || ($isTeacher && $examBelongsToTeacher && $user->id === $gradingRule->teacher_id);
        }

        return $isAdmin  || ($isTeacher && $examBelongsToTeacher);
    }

    public function toggleAllowedClass(User $user, Exam $exam, string $schoolClass): bool
    {
        return $user instanceof Teacher && $user->hasRole(Roles::TEACHER) &&
            $user->id === $exam->teacher_id &&
            $user->lecturesSchoolClass($schoolClass);
    }

    private function isAdminOrOwner(User $user, Exam $exam): bool
    {
        return $user->hasRole(Roles::ADMIN) ||
            ($user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id);
    }
}
