<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Question $question): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($question, ['question:retrieve', 'answer:nestedQuestionRetrieve']));
    }
}
