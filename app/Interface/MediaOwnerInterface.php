<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface MediaOwnerInterface
{
    public function media(): MorphOne;
    public function mediaOwnerId(): int;
}
