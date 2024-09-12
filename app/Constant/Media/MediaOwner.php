<?php

namespace App\Constant\Media;

use App\Constant\Constant;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MediaOwner extends Constant
{
    public final const USER = 'user';
    public final const QUESTION = 'question';

    public static function repository(string $ownerType): string
    {
        return match ($ownerType) {
            self::USER => UserRepository::class,
            self::QUESTION => QuestionRepository::class,
            default => throw new HttpException(500, 'Unknown media owner of type '.$ownerType),
        };
    }
}
