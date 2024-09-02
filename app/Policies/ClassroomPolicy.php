<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\User;

class ClassroomPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN);
    }

    public function update(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN);
    }

    public function delete(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN);
    }

    public function toggleEquipment(User $user): bool    {
        return $user->hasRole(Roles::ADMIN);
    }

    public function updateEquipmentQuantity(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN);
    }

    public function retrieve(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) || $user->hasRole(Roles::TEACHER);
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) || $user->hasRole(Roles::TEACHER);
    }
}
