<?php

namespace App\Constant\Media;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageSize
{
    public const USER_RATIO = 1;
    public const QUESTION_RATIO = 0.5;

    public static function get(string $owner): array
    {
        return match ($owner) {
            MediaOwner::USER => [
                'small' => [
                    'width' => 200,
                    'height' => 200 * self::USER_RATIO
                ],
                'large' => [
                    'width' => 600,
                    'height' => 600 * self::USER_RATIO,
                ],
            ],
            MediaOwner::QUESTION => [
                'small' => [
                    'width' => 400,
                    'height' => 400 * self::QUESTION_RATIO
                ],
                'large' => [
                    'width' => 1200,
                    'height' => 1200 * self::QUESTION_RATIO,
                ],
            ],
            default => throw new HttpException(500, 'Unknown media owner of type '.$owner),
        };
    }

    public static function getRatio(string $owner): float
    {
        return match ($owner) {
            MediaOwner::USER => self::USER_RATIO,
            MediaOwner::QUESTION => self::QUESTION_RATIO,
            default => throw new HttpException(500, 'Unknown media owner of type '.$owner),
        };
    }
}
