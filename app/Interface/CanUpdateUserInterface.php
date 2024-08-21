<?php

namespace App\Interface;

use App\Models\User;

interface CanUpdateUserInterface
{
    public function update(array $data, User $user): User;
}
