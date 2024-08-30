<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Classroom $classroom): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($classroom,
            ['classroom:retrieve', 'equipment:nestedClassroomRetrieve']));
    }
}
