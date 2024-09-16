<?php

namespace App\Http\Controllers\ExamSession;

use App\Models\Exam;
use App\Repository\ExamSessionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private ExamSessionRepository $repository
    ){
    }

    public function __invoke(Request $request, Exam $exam): JsonResponse
    {
        $examSession = $this->repository->create($exam);

        return new JsonResponse(Serializer::normalize($examSession,
            ['examSession:create', 'exam:nestedExamSessionCreate']));
    }
}
