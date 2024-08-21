<?php

namespace App\Http\Controllers\SchoolClass;

use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
