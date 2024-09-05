<?php

namespace App\Http\Controllers\GradeRange;

use App\Models\GradeRange;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(GradeRange $gradeRange): JsonResponse
    {
        $gradeRange->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
