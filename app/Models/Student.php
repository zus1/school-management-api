<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Zus1\Discriminator\Observers\DiscriminatorObserver;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $onboarded_at
 * @property string $last_change_at
 * @property int $guardian_id
 */
#[Attributes([
    ['id',
        'student:create', 'student:onboard', 'user:me', 'student:retrieve',
        'student:collection', 'user:nestedEventToggleNotify', 'student:nestedGradeCreate', 'student:nestedGradeCollection',
        'student:nestedAttendanceCreate', 'student:nestedAttendanceCollection', 'student:nestedAttendanceAggregate'
    ],
    ['onboarded_at', 'student:onboard', 'student:retrieve'],
    ['last_change_at', 'student:update'],
    ['parent',
        'student:create', 'student:onboard', 'user:me', 'student:update', 'student:retrieve',
        'student:collection', 'user:nestedEventToggleNotify', 'student:nestedGradeCreate', 'student:nestedGradeCollection',
        'student:nestedAttendanceCreate', 'student:nestedAttendanceCollection', 'student:nestedAttendanceAggregate'
    ],
])]
#[ObservedBy(DiscriminatorObserver::class)]
class Student extends User
{
    use HasFactory;

    public $timestamps = false;

    public function teacher(): HasOneThrough
    {
        return $this->hasOneThrough(
            Teacher::class,
            SchoolClass::class,
            'id',
            'id',
            'teacher_id',
            'school_class_id',
        );
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }

    public function schoolYear(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolYear::class,
            SchoolClass::class,
            'id',
            'id',
            'school_class_id',
            'school_year_id',
        );
    }

    public function scheduledClasses(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubjectEvent::class,
            SchoolClass::class,
            'id',
            'school_class_id',
            'school_class_id',
            'id',
        );
    }

    public function hasLecturer(Teacher $teacher): bool
    {
        return $this->scheduledClasses()->whereRelation('teacher', 'id', $teacher->id)->exists();
    }

    public function hasSubject(Subject $subject): bool
    {
        /** @var SchoolYear $schoolYear */
        $schoolYear = $this->schoolYear()->first();

        return $schoolYear->subjects()->where('id', $subject->id)->exists();
    }
}
