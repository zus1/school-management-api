<?php

namespace App\Http\Controllers\ExamSession;

use App\Models\ExamSession;
use App\Repository\ExamSessionRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Finish
{
    public function __construct(
        private ExamSessionRepository $repository,
    ){
    }

    public function __invoke(ExamSession $examSession): JsonResponse
    {
        $examSession = $this->repository->finish($examSession);

        return new JsonResponse(Serializer::normalize($examSession, 'examSession:finish'));
    }
}
