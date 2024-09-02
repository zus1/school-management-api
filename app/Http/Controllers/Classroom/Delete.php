<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Classroom;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Classroom $classroom): JsonResponse
    {
        $classroom->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
