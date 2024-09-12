<?php

namespace App\Http\Controllers\ExamResponse;

use App\Http\Requests\ExamResponseRequest;
use App\Models\ExamResponse;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(ExamResponseRequest $request, ExamResponse $examResponse): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($examResponse,
            ['examResponse:retrieve', 'answer:nestedExamResponseRetrieve']));
    }
}
