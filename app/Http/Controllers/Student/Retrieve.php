<?php

namespace App\Http\Controllers\Student;

use App\Dto\RetrieveStudentResponseDto;
use App\Models\Student;
use Illuminate\Http\JsonResponse;

class Retrieve
{
    public function __invoke(Student $student): JsonResponse
    {
        return new JsonResponse(RetrieveStudentResponseDto::create($student));
    }
}
