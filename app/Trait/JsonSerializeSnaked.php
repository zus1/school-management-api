<?php

namespace App\Trait;

use Illuminate\Support\Str;

trait JsonSerializeSnaked
{
    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        $sanitizedVars = [];
        array_walk($vars, function (array $value, string $key) use (&$sanitizedVars) {
            $sanitizedVars[Str::snake($key)] = $value;
        });

        return $sanitizedVars;
    }
}
