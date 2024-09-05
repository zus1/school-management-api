<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface SchoolDirectoryInterface
{
    public function teacher(): BelongsTo;
    public function student(): BelongsTo;
    public function schoolClass(): BelongsTo;
    public function subject(): BelongsTo;
}
