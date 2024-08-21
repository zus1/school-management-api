<?php

namespace App\Constant\Media;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageSize
{
    private const USER_RATIO = 1;

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
            default => throw new HttpException(500, 'Unknown media owner of type '.$owner),
        };
    }
}
