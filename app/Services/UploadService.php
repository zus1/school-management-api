<?php

namespace App\Services;

use App\Constant\Media\ImageSize;
use App\Constant\Media\MediaType;
use App\Interface\MediaOwnerInterface;
use App\Interface\MediasOwnerInterface;
use App\Models\Media;
use App\Repository\MediaRepository;
use App\Services\Aws\S3;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UploadService
{
    public function __construct(
        private MediaRepository $repository,
        private S3 $s3,
        private Logger $logger,
    ){
    }

    public function upload(
        UploadedFile $media,
        MediasOwnerInterface|MediaOwnerInterface $owner,
        string $mediaType,
        string $ownerType
    ): Collection {

        $results = new Collection();

        $this->clear($owner, $mediaType);

        if($mediaType === MediaType::IMAGE) {
            $this->handleImageUpload(
                media: $media,
                results: $results,
                owner: $owner,
                ownerType: $ownerType,
            );
        }
        if($mediaType === MediaType::VIDEO) {
            $this->handleVideoUpload(
                media: $media,
                results: $results,
                owner: $owner,
                ownerType: $ownerType
            );
        }

        return $results;
    }

    private function handleImageUpload(
        UploadedFile $media,
        Collection $results,
        MediasOwnerInterface|MediaOwnerInterface $owner,
        string $ownerType
    ): void {
        foreach (ImageSize::get($ownerType) as $sizeType => $size) {
            $this->resize($media, $size);
            $filename = $this->createFilename($media, $ownerType, MediaType::IMAGE, $sizeType);

            $local = $this->saveLocal($owner, $filename, MediaType::IMAGE);
            $this->saveAws($media, $filename);

            $results->add($local);
        }
    }

    private function handleVideoUpload(
        UploadedFile $media,
        Collection $results,
        MediasOwnerInterface|MediaOwnerInterface $owner,
        string $ownerType
    ): void {
        $filename = $this->createFilename($media, $ownerType, MediaType::VIDEO);

        $local = $this->saveLocal($owner, $filename, MediaType::VIDEO);
        $this->saveAws($media, $filename);

        $results->add($local);
    }

    private function clear(MediasOwnerInterface|MediaOwnerInterface $owner, string $mediaType): void
    {
        DB::beginTransaction();

        try {
            $clearedLocalFilenames = $this->repository->clear($owner, $mediaType);

            if($clearedLocalFilenames->isNotEmpty()) {
                $this->s3->deleteBulk($clearedLocalFilenames->all());
            }
        } catch (\Exception) {
            DB::rollBack();

            $this->logger->error(sprintf('Could not clear media for %s with id %d', $owner::class, $owner->mediaOwnerId()));
        }

        DB::commit();
    }

    private function resize(UploadedFile $media, array $size): void
    {
        $imageProcessor = Image::read($media->getRealPath());
        $imageProcessor->resize($size['width'], $size['height']);
        $imageProcessor->save();
    }

    private function createFilename(UploadedFile $media, string $ownerType, string $mediaType, string $sizeType = ''): string
    {
        return sprintf(
            '%s/%s%s.%s',
            sprintf('%s/%s', Str::plural($ownerType), Str::plural($mediaType)),
            random_int(1, 1000000),
            $sizeType === '' ? '' : '_'.$sizeType,
            $media->getExtension()
        );
    }

    private function saveLocal(MediasOwnerInterface|MediaOwnerInterface $owner, string $filename, string $mediaType): Media
    {
        return $this->repository->create($owner, $filename, $mediaType);
    }

    private function saveAws(UploadedFile $media, string $filename): void
    {
        $url = $this->s3->put($filename, $media->getRealPath());

        if($url === '') {
            $this->logger->error(sprintf('Could not upload media %s to %s', $filename, __FUNCTION__));
        }
    }
}
