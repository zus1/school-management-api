<?php

namespace App\Http\Controllers\Student;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Repository\StudentRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private StudentRepository $repository,
    ){
    }

    public function __invoke(StudentRequest $request, Student $student): JsonResponse
    {
        $student = $this->repository->update($request->input(), $student);

        return new JsonResponse(Serializer::normalize($student, 'student:update'));
    }
}
