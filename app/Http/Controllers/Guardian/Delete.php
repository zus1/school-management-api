<?php

namespace App\Http\Controllers\Guardian;

use App\Models\Guardian;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Guardian $guardian): JsonResponse
    {
        $guardian->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
