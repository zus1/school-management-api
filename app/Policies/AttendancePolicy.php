<?php

namespace App\Policies;

use App\Models\User;

class AttendancePolicy extends SchoolDirectoryPolicy
{
    public function aggregate(): bool
    {
        return true;
    }
}
