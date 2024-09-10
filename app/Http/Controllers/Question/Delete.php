<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Question $question): JsonResponse
    {
        $question->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
