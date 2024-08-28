<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Event;
use App\Models\ExamEvent;
use App\Models\Student;
use App\Models\User;

class ExamEventPolicy extends EventPolicy
{
    public function update(User $user, ExamEvent|Event $event): bool
    {
        return parent::update($user, $event) && $event->creator_id === $user->id;
    }

    public function delete(User $user, ExamEvent|Event $event): bool
    {
        return $event->creator_id === $event->id;
    }

    public function retrieve(User $user, ExamEvent|Event $event): bool
    {
        return $event->creator_id === $user->id ||
            $event->teacher_id === $user->id ||
            ($user instanceof Student && $user->hasRole(Roles::STUDENT) && $event->hasParticipatingStudent($user));
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER) || $user->hasRole(Roles::STUDENT);
    }

    public function toggleNotify(User $user, ExamEvent|Event $event): bool
    {
        return parent::toggleNotify($user, $event) && $event->creator_id === $user->id;
    }

    public function updateStatus(User $user, ExamEvent|Event $event): bool
    {
        return parent::toggleNotify($user, $event) && $event->creator_id === $user->id;
    }

    public function updateRepeatableStatus(User $user, ExamEvent|Event $event): bool
    {
        return parent::toggleNotify($user, $event) && $event->creator_id === $user->id;
    }
}
