<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property int $teacher_id
 * @property int $school_year_id
 */
#[Attributes([
    ['id',
        'schoolClass:create', 'classroomNestedSubjectEventRetrieve', 'schoolClass:nestedExamEventCreate',
        'schoolClass:nestedExamEventRetrieve', 'schoolClass:nestedSubjectTeacherRetrieve', 'schoolClass:nestedGradeCreate',
        'schoolClass:nestedGradeCollection', 'schoolClass:nestedAttendanceCreate', 'schoolClass:nestedAttendanceCollection',
        'schoolClass:nestedAttendanceAggregate'
    ],
    ['name',
        'schoolClass:create', 'schoolClass:update', 'schoolClass:nestedSubjectEventCreate',
        'schoolClass:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve',
        'schoolClass:nestedExamEventCreate', 'schoolClass:nestedExamEventRetrieve',
        'schoolClass:nestedSubjectTeacherRetrieve', 'schoolClass:nestedTeacherSubjectCollection',
        'schoolClass:nestedGradeCreate', 'schoolClass:nestedGradeCollection', 'schoolClass: nestedAttendanceCreate',
        'schoolClass:nestedAttendanceCollection', 'schoolClass:nestedAttendanceAggregate'
    ],
    ['teacher', 'schoolClass:create', 'schoolClass:update'],
    ['schoolYear',
        'schoolClass:create', 'schoolClass:update', 'schoolClass:nestedSubjectTeacherRetrieve',
        'schoolClass:nestedTeacherSubjectCollection'
    ],
])]
class SchoolClass extends Model
{
    use HasFactory;

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school_class_id', 'id');
    }

    public function subjectEvents(): HasMany
    {
        return $this->hasMany(SubjectEvent::class, 'school_class_id', 'id');
    }
}
