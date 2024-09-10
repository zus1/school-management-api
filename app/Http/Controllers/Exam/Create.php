<?php

namespace App\Http\Controllers\Exam;

use App\Http\Requests\ExamRequest;
use App\Repository\ExamRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private ExamRepository $repository,
    ){
    }

    public function __invoke(ExamRequest $request): JsonResponse
    {
        $exam = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($exam,
            ['exam:create', 'subject:nestedExamCreate', 'gradingRule:nestedExamCreate']));
    }
}
