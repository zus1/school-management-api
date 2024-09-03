<?php

namespace App\Trait\Mutator;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait CreatedAtMutator
{
    public function createdAt(): Attribute
    {
        return Attribute::get(fn (string $value) => (new Carbon($value))->toDateTimeString());
    }
}
