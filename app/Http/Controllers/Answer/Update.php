<?php

namespace App\Http\Controllers\Answer;

use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Repository\AnswerRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private AnswerRepository $repository,
    ){
    }

    public function __invoke(AnswerRequest $request, Answer $answer): JsonResponse
    {
        $answer = $this->repository->update($request->input(), $answer);

        return new JsonResponse(Serializer::normalize($answer, ['answer:update', 'question:nestedAnswerUpdate']));
    }
}
