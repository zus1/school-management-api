<?php

namespace App\Http\Controllers\TeacherSubject;

use App\Models\TeacherSubject;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(TeacherSubject $teacherSubject): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($teacherSubject, [
            'teacherSubject:retrieve',
            'teacher:nestedTeacherSubjectRetrieve',
            'subject:nestedTeacherSubjectRetrieve',
            'schoolClass:nestedSubjectTeacherRetrieve',
            'schoolYear:nestedTeacherSubjectRetrieve',
        ]));
    }
}
