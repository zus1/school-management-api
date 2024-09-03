<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $student_id
 * @property int school_class_id
 * @property int $subject_id
 * @property int $grade
 * @property string $comment
 * @property bool $is_final
 */
#[Attributes([
    ['id', 'grade:create', 'grade:collection'],
    ['grade', 'grade:create', 'grade:update', 'grade:collection'],
    ['comment', 'grade:create', 'grade:update', 'grade:collection'],
    ['is_final', 'grade:create', 'grade:collection', 'grade:update'],
    ['teacher', 'grade:create', 'grade:collection'],
    ['student', 'grade:create', 'grade:collection'],
    ['schoolClass', 'grade:create', 'grade:collection'],
    ['subject', 'grade:create', 'grade:collection'],
])]
class Grade extends Model
{
    use HasFactory;

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
