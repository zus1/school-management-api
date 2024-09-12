<?php

namespace App\Http\Controllers\ExamSession;

use App\Models\ExamSession;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(ExamSession $examSession): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($examSession,
            ['examSession:retrieve', 'student:nestedExamSessionRetrieve', 'exam:nestedExamSessionRetrieve']));
    }
}
