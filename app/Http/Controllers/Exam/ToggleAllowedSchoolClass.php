<?php

namespace App\Http\Controllers\Exam;

use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Repository\ExamRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class ToggleAllowedSchoolClass
{
    public function __construct(
        private ExamRepository $repository,
    ){
    }

    public function __invoke(ExamRequest $request, Exam $exam, string $schoolClass): JsonResponse
    {
        $exam = $this->repository->toggleAllowedSchoolClass(
            exam: $exam,
            schoolClass: $schoolClass,
            action: $request->query('action'),
        );

        return new JsonResponse(Serializer::normalize($exam, 'exam:toggleAllowedSchoolClass'));
    }
}
