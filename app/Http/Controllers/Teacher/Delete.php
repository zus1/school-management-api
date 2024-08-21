<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Teacher $teacher): JsonResponse
    {
        $teacher->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
