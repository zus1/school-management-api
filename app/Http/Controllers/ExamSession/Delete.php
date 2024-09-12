<?php

namespace App\Http\Controllers\ExamSession;

use App\Models\ExamSession;
use App\Repository\ExamSessionRepository;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __construct(
        private ExamSessionRepository $repository,
    ){
    }

    public function __invoke(ExamSession $examSession): JsonResponse
    {
        $this->repository->delete($examSession);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
