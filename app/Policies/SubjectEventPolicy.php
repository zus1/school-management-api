<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Event;
use App\Models\SubjectEvent;
use App\Models\User;

class SubjectEventPolicy extends EventPolicy
{
    public function retrieve(User $user, SubjectEvent|Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER) || $user->hasRole(Roles::STUDENT);
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER) || $user->hasRole(Roles::STUDENT);
    }
}
