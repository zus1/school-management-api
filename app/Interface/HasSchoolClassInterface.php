<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasSchoolClassInterface
{
    public function schoolClass(): BelongsTo;
}
