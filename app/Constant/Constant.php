<?php

namespace App\Constant;

class Constant
{
    public static function getValues(): array
    {
        $rf = new \ReflectionClass(static::class);

        $constants = $rf->getConstants(\ReflectionClassConstant::IS_FINAL);

        return array_values($constants);
    }
}
