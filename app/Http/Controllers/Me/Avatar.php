<?php

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Avatar
{
    public function __construct(
        private UploadService $uploadService,
    ){
    }

    public function __invoke(Request $request): JsonResponse
    {
        $image = $request->file('media');
        /** @var User $owner */
        $owner = Auth::user();

        $results = $this->uploadService->upload($image, $owner);

        return new JsonResponse($results);
    }
}
