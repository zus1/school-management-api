<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole(Roles::ADMIN) ? true : null;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function retrieve(User $user, Event $event): bool
    {
        return true;
    }

    public function collection(User $user): bool
    {
        return true;
    }

    public function toggleNotify(User $user, Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function updateRepeatableStatus(User $user, Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }

    public function updateStatus(User $user, Event $event): bool
    {
        return $user->hasRole(Roles::TEACHER);
    }
}
