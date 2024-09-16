<?php

namespace App\Repository;

use App\Constant\Media\MediaOwner;
use App\Interface\MediaOwnerInterface;
use App\Interface\MediasOwnerInterface;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class MediaRepository extends LaravelBaseRepository
{
    protected const MODEL = Media::class;

    public function create(MediasOwnerInterface|MediaOwnerInterface $owner, string $filename, string $type): Media
    {
        $media = new Media();
        $media->media = $filename;
        $media->type = $type;

        $media->mediaOwner()->associate($owner);

        $media->save();

        return $media;
    }

    public function clear(MediasOwnerInterface|MediaOwnerInterface $owner, string $mediaType): \Illuminate\Support\Collection
    {
        $filenames = $this->getClearBuilder($owner, $mediaType)->pluck('media');

        $this->getClearBuilder($owner, $mediaType)->delete();

        return $filenames;
    }

    private function getClearBuilder(MediasOwnerInterface|MediaOwnerInterface $owner, string $mediaType): Builder
    {
        return $this->getBuilder()
            ->where('type', $mediaType)
            ->whereMorphRelation('mediaOwner', $owner::class, 'id', $owner->id);
    }
}
