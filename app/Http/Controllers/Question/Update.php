<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use App\Repository\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private QuestionRepository $repository,
    ){
    }

    public function __invoke(Request $request, Question $question): JsonResponse
    {
        $question = $this->repository->update($request->input(), $question);

        return new JsonResponse(Serializer::normalize($question, ['question:update', 'exam:nestedQuestionUpdate']));
    }
}
