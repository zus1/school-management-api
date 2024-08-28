<?php

namespace App\Http\Controllers\Subject;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Subject $subject): JsonResponse
    {
        $subject->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
