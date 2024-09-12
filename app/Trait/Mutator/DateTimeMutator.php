<?php

namespace App\Trait\Mutator;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait DateTimeMutator
{
    private function mutateDateTime(): Attribute
    {
        return new Attribute(
            get: fn (string $value) => (new Carbon($value))->toDateTimeString(),
            set: fn (string $value) => (new Carbon($value))->toDateTimeString(),
        );
    }
}
