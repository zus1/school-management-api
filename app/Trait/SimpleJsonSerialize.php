<?php

namespace App\Trait;

use Illuminate\Support\Str;

trait SimpleJsonSerialize
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
