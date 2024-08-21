<?php

namespace App\Dto;

use App\Models\Media;
use Illuminate\Support\Str;

class UploadResponseDto implements \JsonSerializable
{
    private string $media;
    private string $mediaType;
    private bool $awsUploaded;

    public static function create(Media $media, bool $awsUploaded): self
    {
        $instance = new self();
        $instance->media = $media->media;
        $instance->mediaType = $media->type;
        $instance->awsUploaded = $awsUploaded;

        return $instance;
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        $sanitized = [];
        array_walk($vars, function (mixed $value, string $key) use (&$sanitized) {
            $sanitized[Str::snake($key)] = $value;
        });

        return $sanitized;
    }
}
