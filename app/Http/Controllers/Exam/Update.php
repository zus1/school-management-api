<?php

namespace App\Http\Controllers\Exam;

use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Repository\ExamRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private ExamRepository $repository,
    ){
    }

    public function __invoke(ExamRequest $request, Exam $exam): JsonResponse
    {
        $exam = $this->repository->update($request->input(), $exam);

        return new JsonResponse(Serializer::normalize($exam, ['exam:update', 'subject:nestedExamUpdate']));
    }
}
