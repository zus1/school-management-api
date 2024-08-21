<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Teacher $teacher): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($teacher, ['teacher:retrieve', 'media:nestedTeacherRetrieve']));
    }
}
