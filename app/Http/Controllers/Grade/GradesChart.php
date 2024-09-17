<?php

namespace App\Http\Controllers\Grade;

use App\Http\Requests\GradeRequest;
use App\Repository\GradeRepository;
use Illuminate\Http\JsonResponse;

class GradesChart
{
    public function __construct(
        private GradeRepository $repository
    ){
    }

    public function __invoke(GradeRequest $request): JsonResponse
    {
        $gradeData = $this->repository->findForGradeAnalytics($request->query());

        return new JsonResponse($gradeData);
    }
}
