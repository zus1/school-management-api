<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Requests\ClassroomRequest;
use App\Repository\ClassroomRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private ClassroomRepository $repository,
    ){
    }

    public function __invoke(ClassroomRequest $request): JsonResponse
    {
        $classroom = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($classroom, 'classroom:create'));
    }
}
