<?php

namespace App\Http\Controllers\GradeRange;

use App\Http\Requests\GradeRangeRequest;
use App\Models\GradeRange;
use App\Repository\GradeRangeRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private GradeRangeRepository $repository,
    ){
    }

    public function __invoke(GradeRangeRequest $request, GradeRange $gradeRange): JsonResponse
    {
        $gradeRange = $this->repository->update($request->input(), $gradeRange);

        return new JsonResponse(Serializer::normalize($gradeRange,
            ['gradeRange:update', 'gradingRule:nestedGradeRangeUpdate']));
    }
}
