<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $is_elective
 * @property int $school_year_id
 */
#[Attributes([
    ['id',
        'subject:create', 'subject:nestedExamEventCreate', 'subject:nestedExamEventUpdate',
        'subject:nestedExamEventRetrieve', 'subject:retrieve', 'subject:collection', 'subject:toggleLecturer',
        'subject:nestedTeacherSubjectRetrieve', 'subject:nestedGradeCreate', 'subject:nestedGradeCollection',
        'subject:nestedAttendanceCreate', 'subject:nestedAttendanceCollection', 'subject:nestedAttendanceAggregate'
    ],
    ['name',
        'subject:create', 'subject:update', 'subject:nestedExamEventCreate', 'subject:nestedExamEventUpdate',
        'subject:nestedExamEventRetrieve', 'subject:retrieve', 'subject:collection',
        'subject:nestedTeacherSubjectRetrieve', 'subject:nestedTeacherSubjectCollection',
        'subject:toggleLecturerClasses', 'subject:toggleLecturer', 'subject:nestedGradeCreate',
        'subject:nestedGradeCollection', 'subject:nestedAttendanceCreate', 'subject:nestedAttendanceCollection',
        'subject:nestedAttendanceAggregate'
    ],
    ['description', 'subject:create', 'subject:update', 'subject:retrieve', 'subject:nestedTeacherSubjectRetrieve'],
    ['is_elective',
        'subject:create', 'subject:update', 'subject:retrieve', 'subject:collection',
        'subject:nestedTeacherSubjectRetrieve', 'subject:nestedTeacherSubjectCollection'
    ],
    ['lecturers', 'subject:retrieve'],
    ['schoolYear', 'subject:update', 'subject:retrieve', 'subject:collection']
])]
class Subject extends Model
{
    use HasFactory;

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teachers_subjects', 'subject_id', 'teacher_id');
            //->using(TeacherSubject::class);
    }

    public function casts(): array
    {
        return [
            'is_elective' => 'boolean'
        ];
    }
}
