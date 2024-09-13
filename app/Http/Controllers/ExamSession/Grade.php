<?php

namespace App\Http\Controllers\ExamSession;

use App\Models\ExamSession;
use App\Services\ExamGradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Grade
{
    public function __construct(
        private ExamGradeService $gradeService,
    ){
    }

    public function __invoke(Request $request, ExamSession $examSession): JsonResponse
    {
        $gradedExamSession = $this->gradeService->gradeBulk($request->input(), $examSession);

        return new JsonResponse(Serializer::normalize($gradedExamSession,
            ['examSession:grade', 'examResponses:nestedExamSessionGrade', 'answer:nestedExamSessionGrade']));
    }
}
