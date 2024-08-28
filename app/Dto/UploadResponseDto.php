<?php

namespace App\Dto;

use App\Models\Media;
use App\Trait\JsonSerializeSnaked;
use Illuminate\Support\Str;

class UploadResponseDto implements \JsonSerializable
{
    use JsonSerializeSnaked;

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
}
