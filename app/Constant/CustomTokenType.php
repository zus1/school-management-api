<?php

namespace App\Constant;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelAuth\Constant\Token\TokenType;

class CustomTokenType extends TokenType
{
    public final const PHONE_VERIFICATION = 'phone_verification';
    public final const INVITATION = 'invitation';

    protected static function customExpiresAt(Carbon $createdAt, $type): Carbon
    {
        if($type === static::PHONE_VERIFICATION) {
            return $createdAt->addMinutes(30);
        }
        if($type === static::INVITATION) {
            return $createdAt->addDay();
        }

        throw new HttpException(500, 'Unknown token type '.$type);
    }

    protected static function customLength($type): int
    {
        if($type === static::PHONE_VERIFICATION) {
            return 6;
        }
        if($type === static::INVITATION) {
            return 50;
        }

        throw new HttpException(500, 'Unknown token length '.$type);
    }

    protected static function customAction($type): string
    {
        if ($type === static::PHONE_VERIFICATION) {
            return 'getCode';
        }

        return 'getToken';
    }
}
