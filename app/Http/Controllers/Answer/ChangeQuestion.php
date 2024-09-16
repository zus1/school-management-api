<?php

namespace App\Http\Controllers\Answer;

use App\Models\Answer;
use App\Models\Question;
use App\Repository\AnswerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class ChangeQuestion
{
    public function __construct(
        private AnswerRepository $repository,
    ){
    }

    public function __invoke(Request $request, Answer $answer, Question $question): JsonResponse
    {
        $answer = $this->repository->changeQuestion($answer, $question);

        return new JsonResponse(Serializer::normalize($answer,
            ['answer:changeQuestion', 'question:nestedAnswerChangQuestion']));
    }
}
