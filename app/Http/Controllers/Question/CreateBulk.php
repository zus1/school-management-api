<?php

namespace App\Http\Controllers\Question;

use App\Dto\QuestionsCreateResponse;
use App\Models\Exam;
use App\Repository\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateBulk
{
    public function __construct(
        private QuestionRepository $repository,
    ){
    }

    public function __invoke(Request $request, Exam $exam): JsonResponse
    {
        $questions = $this->repository->creatBulk($request->input('questions'), $exam);

        return new JsonResponse(QuestionsCreateResponse::create($questions, $exam));
    }
}
