<?php

namespace App\Services;

use App\Constant\Media\ImageSize;
use App\Constant\Media\MediaOwner;
use App\Constant\Media\MediaType;
use App\Dto\UploadResponseDto;
use App\Models\Media;
use App\Models\User;
use App\Repository\MediaRepository;
use App\Services\Aws\S3;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UploadService
{
    public function __construct(
        private MediaRepository $repository,
        private S3 $s3,
    ){
    }

    public function upload(UploadedFile $media, User $owner): Collection
    {
        $mediaType = MediaType::IMAGE;
        $ownerType = MediaOwner::USER;
        $results = new Collection();

        $cleared = $this->clear($owner, $mediaType);
        $results->add(['cleared' => $cleared]);

        foreach (ImageSize::get($ownerType) as $sizeType => $size) {
            $this->resize($media, $size);
            $filename = $this->createFilename($media, $ownerType, $mediaType, $sizeType);

            $local = $this->saveLocal($owner, $filename, $mediaType);
            $awsSaved = $this->saveAws($media, $filename);

            $results->add(UploadResponseDto::create($local, $awsSaved));
        }

        return $results;
    }

    private function clear(User $owner, string $mediaType): bool
    {
        DB::beginTransaction();

        try {
            $clearedLocalFilenames = $this->repository->clear($owner, $mediaType);

            if($clearedLocalFilenames->isNotEmpty()) {
                $this->s3->deleteBulk($clearedLocalFilenames->all());
            }
        } catch (\Exception) {
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }

    private function resize(UploadedFile $media, array $size): void
    {
        $imageProcessor = Image::read($media->getRealPath());
        $imageProcessor->resize($size['width'], $size['height']);
        $imageProcessor->save();
    }

    private function createFilename(UploadedFile $media, string $ownerType, string $mediaType, string $sizeType): string
    {
        return sprintf(
            '%s/%s_%s.%s',
            sprintf('%s/%s', Str::plural($ownerType), Str::plural($mediaType)),
            random_int(1, 1000000),
            $sizeType,
            $media->getExtension()
        );
    }

    private function saveLocal(User $owner, string $filename, string $mediaType): Media
    {
        return $this->repository->create($owner, $filename, $mediaType);
    }

    private function saveAws(UploadedFile $media, string $filename): bool
    {
        $url = $this->s3->put($filename, $media->getRealPath());

        return $url !== '';
    }
}
