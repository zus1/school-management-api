<?php

namespace App\Constant;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageUserType extends Constant
{
    public final const SENDER = 'sender';
    public final const RECIPIENT = 'recipient';

    public static function withSuffix(string $type): string
    {
        if(!in_array($type, static::getValues())) {
            throw new HttpException(500, 'Unknown message user type '.$type);
        }

        return sprintf('%s_type', $type);
    }
}
