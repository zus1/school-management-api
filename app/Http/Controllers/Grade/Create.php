<?php

namespace App\Http\Controllers\Grade;

use App\Http\Requests\GradeRequest;
use App\Repository\GradeRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private GradeRepository $repository,
    ){
    }

    public function __invoke(GradeRequest $request): JsonResponse
    {
        $grade = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($grade, [
            'grade:create',
            'teacher:nestedGradeCreate',
            'student:nestedGradeCreate',
            'schoolClass:nestedGradeCreate',
            'subject:nestedGradeCreate'
        ]));
    }
}
