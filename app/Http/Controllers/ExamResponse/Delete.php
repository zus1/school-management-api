<?php

namespace App\Http\Controllers\ExamResponse;

use App\Models\ExamResponse;
use App\Repository\ExamResponseRepository;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __construct(
        private ExamResponseRepository $repository,
    ){
    }

    public function __invoke(ExamResponse $examResponse): JsonResponse
    {
        $this->repository->delete($examResponse);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
