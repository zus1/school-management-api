<?php

namespace App\Http\Controllers\Grade;

use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use App\Repository\GradeRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private GradeRepository $repository,
    ){
    }

    public function __invoke(GradeRequest $request, Grade $grade): JsonResponse
    {
        $grade = $this->repository->update($request->input(), $grade);

        return new JsonResponse(Serializer::normalize($grade, 'grade:update'));
    }
}
