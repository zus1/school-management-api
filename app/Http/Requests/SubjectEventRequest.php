<?php

namespace App\Http\Requests;

use App\Repository\SubjectEventRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubjectEventRequest extends EventRequest
{
    public function __construct(
        private SubjectEventRepository $repository,
    ){
        parent::__construct();
    }

    protected function passedValidation(): void
    {
        $this->checkAvailability();
    }

    protected function createRules(): array
    {
        return [
            ...parent::createRules(),
            ...$this->sharedRules(),
        ];
    }

    protected function updateRules(): array
    {
        return [
            ...parent::updateRules(),
            ...$this->sharedRules(),
        ];
    }

    private function checkAvailability(): void
    {
        $teacherId = $this->input('teacher_id');
        $schoolClassId = $this->input('school_class_id');
        $classroomId = $this->input('classroom_id');

        $conflictingEvent = $this->repository->findAlreadyScheduled(
            startsAt: $this->input('starts_at'),
            endsAt: $this->input('ends_at'),
            teacherId: $teacherId,
            schoolClassId: $schoolClassId,
            classroomId: $classroomId,
        );

        if($conflictingEvent === null) {
            return;
        }

        if($conflictingEvent->teacher_id === $teacherId) {
            throw new HttpException(422, 'Teacher already has scheduled class');
        }
        if($conflictingEvent->school_class_id === $schoolClassId) {
            throw new HttpException(422, 'School class has another subject scheduled');
        }
        if($conflictingEvent->classroom_id === $classroomId) {
            throw new HttpException(422, 'Classroom is already scheduled for another event');
        }
    }

    private function sharedRules(): array
    {
        return [
            'teacher_id' => 'required|integer|exists:teachers,id',
            'school_class_id' => 'required|integer|exists:school_classes,id',
            'classroom_id' => 'required|integer|exists:classrooms,id',
        ];
    }
}
