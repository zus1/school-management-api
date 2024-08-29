<?php

namespace App\Dto\Repository;

use App\Models\Subject;
use App\Models\Teacher;
use App\Trait\SimpleJsonSerialize;
use Zus1\Serializer\Facade\Serializer;

class ToggleLecturerClassesResponseDto implements \JsonSerializable
{
    use SimpleJsonSerialize;

    private array $subject;
    private array $lecturer;
    private array $schoolClasses;

    public static function create(Subject $subject, Teacher $lecturer): self
    {
        $instance = new self();
        $instance->schoolClasses = $subject->lecturers()
            ->where('teachers.id'. $lecturer->id)
            ->withPivot('school_class_id')
            ->pluck('school_class_id')->all();
        $instance->subject = Serializer::normalize($subject, 'subjectNestedToggleLecturerClasses');
        $instance->lecturer = Serializer::normalize($lecturer, 'teacher:nestedToggleLecturerClasses');

        return $instance;
    }
}
