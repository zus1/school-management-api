<?php

namespace App\Dto;

use App\Models\Student;
use Zus1\Serializer\Facade\Serializer;

class RetrieveStudentResponseDto implements \JsonSerializable
{
    private array $student;

    public static function create(Student $student): self
    {
        $instance = new self();

        $instance->student = [
            ...Serializer::normalize($student, ['student:retrieve', 'media:nestedStudentRetrieve', 'tuition:nestedStudentRetrieve']),
            'is_tuition_paid' => $student->isTuitionPaid(),
        ];

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return $this->student;
    }
}
