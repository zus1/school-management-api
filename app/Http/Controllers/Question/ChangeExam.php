<?php

namespace App\Http\Controllers\Question;

use App\Models\Exam;
use App\Models\Question;
use App\Repository\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class ChangeExam
{
    public function __construct(
        private QuestionRepository $repository,
    ){
    }

    public function __invoke(Question $question, Exam $exam):  JsonResponse
    {
        $question = $this->repository->changeExam($question, $exam);

        return new JsonResponse(Serializer::normalize($question,
            ['question:changeExam', 'exam:nestedQuestionChangeExam']));
    }
}
