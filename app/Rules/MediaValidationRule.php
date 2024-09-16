<?php

namespace App\Rules;

use App\Constant\Media\ImageSize;
use App\Constant\Media\MediaType;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MediaValidationRule implements ValidationRule, DataAwareRule
{
    private array $data;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$value instanceof UploadedFile) {
            throw new HttpException(500, 'validation subject value mismatch');
        }

        $mediaType = $this->data['media_type'];

        if($mediaType === MediaType::VIDEO) {
            $this->validateMimeType(
                media: $value,
                attribute: $attribute,
                allowedMimeTypes: ['video/mp4', 'video/mpeg', 'video/quicktime'],
                fail: $fail
            );

            $this->validateSize($value, $attribute, max: 5248800, fail: $fail);
        }

        if($mediaType === MediaType::IMAGE) {
            $ownerType = $this->data['owner_type'];

            $this->validateMimeType(
                media: $value,
                attribute: $attribute,
                allowedMimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
                fail: $fail
            );

            $this->validateSize($value, $attribute, max: 2097152, fail: $fail);

            $this->validateImageRatio($value, $ownerType, $fail);
        }
    }

    private function validateMimeType(UploadedFile $media, string $attribute, array $allowedMimeTypes, \Closure $fail): void
    {
        if(!in_array($mimeType = $media->getMimeType(), $allowedMimeTypes)) {
            $fail(sprintf('Type %s for field %s is not supported', $mimeType, $attribute));
        }
    }

    private function validateSize(UploadedFile $media, string $attribute, int $max, \Closure $fail): void
    {
        if($media->getSize() > $max) {
            $fail(sprintf('Size of %s can\'t be greater then %d', $attribute, $max));
        }
    }

    private function validateImageRatio(UploadedFile $media, string $ownerType, \Closure $fail): void
    {
        $dimensions = $media->dimensions();
        $ratio = $dimensions[1]/$dimensions[0]; //height/width

        [$requiredLower, $requiredUpper] = $this->getImageRatioBoundaries($ownerType);

        if($ratio < $requiredLower || $ratio > $requiredUpper) {
            $fail(sprintf('Image ratio must be in interval [%f, %f], %f given', $requiredLower, $requiredUpper, $ratio));
        }
    }

    private function getImageRatioBoundaries(string $ownerType): array
    {
        $requiredRatio = ImageSize::getRatio($ownerType);

        return [
            $requiredRatio - 0.1,
            $requiredRatio + 0.1,
        ];
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
