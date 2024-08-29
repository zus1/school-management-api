<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $subject_id
 * @property int $school_class_id
 */
#[Attributes([
    ['id', 'teacherSubject:retrieve', 'teacherSubject:collection'],
    ['lecturer', 'teacherSubject:retrieve', 'teacherSubject:collection'],
    ['subject', 'teacherSubject:retrieve', 'teacherSubject:collection'],
    ['schoolClass', 'teacherSubject:retrieve', 'teacherSubject:collection'],
])]
class TeacherSubject extends Model
{
    use HasFactory;

    public $table = 'teachers_subjects';
    public $timestamps = false;

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }
}
