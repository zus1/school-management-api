<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Exam $exam): JsonResponse
    {
        $exam->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
