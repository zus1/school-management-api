<?php

namespace App\Http\Controllers\Subject;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Subject $subject): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($subject,
            ['subject:retrieve', 'schoolYear:nestedSubjectRetrieve', 'teacher:nestedSubjectRetrieve']));
    }
}
