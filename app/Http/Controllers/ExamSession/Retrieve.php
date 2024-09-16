<?php

namespace App\Http\Controllers\ExamSession;

use App\Http\Requests\ExamSessionRequest;
use App\Models\ExamSession;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(ExamSessionRequest $request, ExamSession $examSession): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($examSession,
            ['examSession:retrieve', 'student:nestedExamSessionRetrieve', 'exam:nestedExamSessionRetrieve']));
    }
}
