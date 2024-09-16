<?php

namespace App\Http\Controllers\Answer;

use App\Models\Answer;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Answer $answer): JsonResponse
    {
        $answer->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
