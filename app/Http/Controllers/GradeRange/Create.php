<?php

namespace App\Http\Controllers\GradeRange;

use App\Http\Requests\GradeRangeRequest;
use App\Models\GradingRule;
use App\Repository\GradeRangeRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private GradeRangeRepository $repository,
    ){
    }

    public function __invoke(GradeRangeRequest $request, GradingRule $gradingRule): JsonResponse
    {
        $gradeRange = $this->repository->create($request->input(), $gradingRule);

        return new JsonResponse(Serializer::normalize($gradeRange,
            ['gradeRange:create', 'gradingRule:nestedGradeRangeCreate']));
    }
}
