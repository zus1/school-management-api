<?php

namespace App\Models;

use App\Interface\SchoolDirectoryInterface;
use App\Trait\Mutator\CreatedAtMutator;
use App\Trait\SchoolDirectory\HasSchoolDirectoryRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $student_id
 * @property int $school_class_id
 * @property int $subject_id
 * @property string $comment
 */
#[Attributes([
    ['id', 'attendance:create', 'attendance:collection'],
    ['count', 'attendance:aggregate'],
    ['comment', 'attendance:create', 'attendance:collection', 'attendance:update'],
    ['created_at', 'attendance:create', 'attendance:collection'],
    ['teacher', 'attendance:create', 'attendance:collection', 'attendance:aggregate'],
    ['student', 'attendance:create', 'attendance:collection', 'attendance:aggregate'],
    ['schoolClass', 'attendance:create', 'attendance:collection', 'attendance:aggregate'],
    ['subject', 'attendance:create', 'attendance:collection', 'attendance:aggregate'],
])]
class Attendance extends Model implements SchoolDirectoryInterface
{
    use HasFactory, HasSchoolDirectoryRelationships, CreatedAtMutator;

    public $dateFormat = 'Y-m-d H:i:s';
}
