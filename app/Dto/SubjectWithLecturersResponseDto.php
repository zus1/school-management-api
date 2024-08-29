<?php

namespace App\Dto;

use App\Models\Subject;
use App\Models\Teacher;
use App\Trait\SimpleJsonSerialize;
use Zus1\Serializer\Facade\Serializer;

class SubjectWithLecturersResponseDto implements \JsonSerializable
{
    use SimpleJsonSerialize;

    private array $subject;
    private array $schoolYear;
    private array $lecturers = [];

    public static function create(Subject $subject, string $serializationGroup): self
    {
        $instance = new self();
        $instance->schoolYear = Serializer::normalize($subject->schoolYear()->first(), 'schoolYear:nestedSubject');
        $instance->subject = Serializer::normalize($subject, $serializationGroup);
        $instance->lecturers = self::setLecturers($subject);

        return $instance;
    }

    private static function setLecturers(Subject $subject): array
    {
        $lecturers = $subject->lecturers()->withPivot('school_class_id')->get();

        if($lecturers->isEmpty()) {
            return [];
        }

        return $lecturers->map(function (Teacher $lecturer) {
            $lecturerNormalized = Serializer::normalize($lecturer, 'teacher:nestedSubject');
            $lecturerNormalized['school_class_id'] = $lecturer->toArray()['pivot']['school_class_id'];

            return $lecturerNormalized;
        })->all();
    }
}
