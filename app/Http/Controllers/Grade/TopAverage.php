<?php

namespace App\Http\Controllers\Grade;

use App\Http\Requests\GradeRequest;
use App\Repository\GradeRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class TopAverage
{
    public function __construct(
        private GradeRepository $repository,
    ){
    }

    public function __invoke(GradeRequest $request): JsonResponse
    {
        $topGrades = $this->repository->findTopAverageGrades($request->query());

        return new JsonResponse(Serializer::normalize($topGrades, [
            'grade:topAverage',
            'teacher:nestedTopAverageGrades',
            'student:nestedTopAverageGrades',
            'subject:nestedTopAverageGrades',
            'schoolClass:nestedTopAverageGrades',
        ]));
    }
}
