<?php

namespace App\Http\Controllers\Grade;

use App\Models\Grade;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Grade $grade): JsonResponse
    {
        $grade->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
