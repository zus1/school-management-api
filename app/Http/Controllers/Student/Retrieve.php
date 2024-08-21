<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Student $student): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($student, ['student:retrieve', 'media:nestedStudentRetrieve']));
    }
}
