<?php

namespace App\Interface;

use App\Models\User;

interface CanRegisterUserInterface
{
    public function register(array $data): User;
}
