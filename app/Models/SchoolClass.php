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
        'schoolClass:nestedExamEventRetrieve'
    ],
    ['name',
        'schoolClass:create', 'schoolClass:update', 'schoolClass:nestedSubjectEventCreate',
        'schoolClass:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve',
        'schoolClass:nestedExamEventCreate', 'schoolClass:nestedExamEventRetrieve'
    ],
    ['teacher', 'schoolClass:create', 'schoolClass:update'],
    ['schoolYear', 'schoolClass:create', 'schoolClass:update'],
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
