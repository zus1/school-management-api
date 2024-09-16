<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\GradeRange;
use App\Models\GradingRule;
use App\Models\Teacher;
use App\Models\User;

class GradeRangePolicy
{
    public function create(User $user, GradingRule $gradingRule): bool
    {
        return $user->hasRole(Roles::TEACHER) && $user->id === $gradingRule->teacher_id;
    }

    public function update(User $user, GradeRange $gradeRange): bool
    {
        return $this->isUserOwner($user, $gradeRange);
    }

    public function delete(User $user, GradeRange $gradeRange): bool
    {
        return $this->isUserOwner($user, $gradeRange);
    }

    private function isUserOwner(User $user, GradeRange $gradeRange): bool
    {
        /** @var Teacher $teacher */
        $teacher = $gradeRange->teacher()->first();

        return $user->id = $teacher->id;
    }
}
