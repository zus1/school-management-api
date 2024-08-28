<?php

namespace App\Http\Controllers\Subject;

use App\Models\Subject;
use App\Models\Teacher;
use App\Repository\SubjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class ToggleLecturer
{
    public function __construct(
        private SubjectRepository $repository,
    ){
    }

    public function __invoke(Request $request, Subject $subject, Teacher $teacher): JsonResponse
    {
        $subject = $this->repository->toggleLecturer(
            subject: $subject,
            lecturer: $teacher,
            action: $request->query('action'),
            schoolClassIds: $request->input('school_class_ids'),
        );

        return new JsonResponse(Serializer::normalize($subject, ['subject:toggleLecturer', 'teacher:nestedSubjectToggleLecturer']));
    }
}
