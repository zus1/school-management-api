<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasTeacherInterface
{
    public function teacher(): BelongsTo;
}
