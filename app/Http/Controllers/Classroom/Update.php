<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Repository\ClassroomRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private ClassroomRepository $repository,
    ){
    }

    public function __invoke(ClassroomRequest $request, Classroom $classroom): JsonResponse
    {
        $classroom = $this->repository->update($request->input(), $classroom);

        return new JsonResponse(Serializer::normalize($classroom, 'classroom:update'));
    }
}
