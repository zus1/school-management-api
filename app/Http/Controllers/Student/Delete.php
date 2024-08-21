<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Student $student): JsonResponse
    {
        $student->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
