<?php

namespace App\Http\Controllers\ExamResponse;

use App\Http\Requests\ExamResponseRequest;
use App\Models\ExamSession;
use App\Repository\ExamResponseRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private ExamResponseRepository $repository,
    ){
    }

    public function __invoke(ExamResponseRequest $request, ExamSession $examSession): JsonResponse
    {
        $examResponse = $this->repository->create($request->input(), $examSession);

        return new JsonResponse(Serializer::normalize($examResponse,
            ['examResponse:create', 'question:nestedExamResponseCreate', 'answer:nextedExamResponseCreate']));
    }
}
