<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Repository\TeacherRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private TeacherRepository $repository,
    ){
    }

    public function __invoke(TeacherRequest $request, Teacher $teacher): JsonResponse
    {
        $teacher = $this->repository->update($request->input(), $teacher);

        return new JsonResponse(Serializer::normalize($teacher, 'teacher:update'));
    }
}
