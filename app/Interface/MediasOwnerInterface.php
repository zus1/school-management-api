<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface MediasOwnerInterface
{
    public function medias(): MorphMany;
    public function mediaOwnerId(): int;
}
