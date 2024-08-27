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
 */
#[Attributes([
    ['id', 'subject:nestedExamEventCreate', 'subject:nestedExamEventUpdate', 'subject:nestedExamEventRetrieve'],
    ['name', 'subject:nestedExamEventCreate', 'subject:nestedExamEventUpdate', 'subject:nestedExamEventRetrieve'],
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
        return $this->belongsToMany(Teacher::class, 'teachers_subjects', 'subject_id', 'teacher_id')
            ->using(TeacherSubject::class);
    }

    public function casts(): array
    {
        return [
            'is_elective' => 'boolean'
        ];
    }
}
