<?php

namespace App\Models;

use App\Interface\HasSchoolClassInterface;
use App\Interface\HasTeacherInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Zus1\Discriminator\Observers\DiscriminatorObserver;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $subject_id
 * @property int $school_class_id
 * @property int $teacher_id
 */
#[Attributes([
    ['id', 'event:create', 'event:retrieve'],
    ['parent', 'event:create', 'event:update', 'event:retrieve'],
    ['schoolClass', 'event:create', 'event:update', 'event:retrieve'],
    ['subject', 'event:create', 'event:update', 'event:retrieve'],
    ['teacher', 'event:create', 'event:update', 'event:retrieve'],
])]
#[ObservedBy(DiscriminatorObserver::class)]
class ExamEvent extends Event implements HasTeacherInterface, HasSchoolClassInterface
{
    use HasFactory;

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            SchoolClass::class,
            'id',
            'school_class_id',
            'school_class_id',
            'id'
        );
    }

    public function hasParticipatingStudent(Student $student): bool
    {
        return $this->students()->where('students.id', $student->id)->exists();
    }
}
