<?php

namespace App\Http\Controllers\Media;

use App\Http\Requests\MediaRequest;
use App\Interface\MediaOwnerInterface;
use App\Interface\MediasOwnerInterface;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Upload
{
    public function __construct(
        private UploadService $uploadService,
    ){
    }

    public function __invoke(MediaRequest $request, MediasOwnerInterface|MediaOwnerInterface $owner): JsonResponse
    {
        $medias = $this->uploadService->upload(
            media: $request->file('media'),
            owner: $owner,
            mediaType: $request->query('media_type'),
            ownerType: $request->query('owner_type')
        );

        return new JsonResponse(Serializer::normalize($medias, ['media:upload', 'mediaOwner:nestedMediaUpload']));
    }
}
