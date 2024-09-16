<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\GradingRule;
use App\Models\Teacher;
use App\Models\User;

class GradingRulePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function update(User $user, GradingRule $gradingRule): bool
    {
        return $user instanceof Teacher && $user->hasRole(Roles::TEACHER) && $user->id === $gradingRule->teacher_id;
    }

    public function delete(User $user, GradingRule $gradingRule): bool
    {
        return $user instanceof Teacher && $user->hasRole(Roles::TEACHER) && $user->id === $gradingRule->teacher_id;
    }

    public function retrieve(User $user, GradingRule $gradingRule): bool
    {
        return $user instanceof Teacher && $user->hasRole(Roles::TEACHER) && $user->id === $gradingRule->teacher_id;
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }
}
