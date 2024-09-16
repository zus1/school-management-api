<?php

namespace App\Http\Controllers\Exam;

use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Models\GradingRule;
use App\Repository\ExamRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class ToggleGradingRule
{
    public function __construct(
        private ExamRepository $repository,
    ){
    }

    public function __invoke(ExamRequest $request, Exam $exam, ?GradingRule $gradingRule = null): JsonResponse
    {
        $exam = $this->repository->toggleGradingRule(
            exam: $exam,
            action: $request->query('action'),
            gradingRule: $gradingRule,
        );

        return new JsonResponse(Serializer::normalize($exam,
            ['exam:toggleGradingRule', 'gradingRule:nestedExamToggleGradingRule']));
    }
}
