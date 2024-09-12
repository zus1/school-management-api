<?php

namespace App\Http\Controllers\ExamResponse;

use App\Http\Requests\ExamResponseRequest;
use App\Models\ExamResponse;
use App\Repository\ExamResponseRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private ExamResponseRepository $repository,
    ){
    }

    public function __invoke(ExamResponseRequest $request, ExamResponse $examResponse): JsonResponse
    {
        $examResponse = $this->repository->update($request->input(), $examResponse);

        return new JsonResponse(Serializer::normalize($examResponse, ['examResponse:update', 'answer:nestedExamUpdate']));
    }
}
