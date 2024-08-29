<?php

namespace App\Http\Controllers\Subject;

use App\Dto\SubjectWithLecturersResponseDto;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Repository\SubjectRepository;
use Illuminate\Http\JsonResponse;

class ToggleLecturerClasses
{
    public function __construct(
        private SubjectRepository $repository
    ){
    }

    public function __invoke(SubjectRequest $request, Subject $subject, Teacher $teacher): JsonResponse
    {
        $subject = $this->repository->toggleLecturerClasses(
            subject: $subject,
            lecturer: $teacher,
            action: $request->query('action'),
            schoolClassIds: $request->input('school_class_ids'),
        );

        return new JsonResponse(SubjectWithLecturersResponseDto::create($subject, 'subject:toggleLecturerClasses'));
    }
}
