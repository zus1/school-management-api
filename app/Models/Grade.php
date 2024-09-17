<?php

namespace App\Models;

use App\Interface\SchoolDirectoryInterface;
use App\Trait\SchoolDirectory\HasSchoolDirectoryRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property float $avg
 */
#[Attributes([
    ['id', 'grade:create', 'grade:collection'],
    ['grade', 'grade:create', 'grade:update', 'grade:collection'],
    ['comment', 'grade:create', 'grade:update', 'grade:collection'],
    ['is_final', 'grade:create', 'grade:collection', 'grade:update'],
    ['avg', 'grade:topAverage'],
    ['teacher', 'grade:create', 'grade:collection', 'grade:topAverage'],
    ['student', 'grade:create', 'grade:collection', 'grade:topAverage'],
    ['schoolClass', 'grade:create', 'grade:collection', 'grade:topAverage'],
    ['subject', 'grade:create', 'grade:collection', 'grade:topAverage'],
])]
class Grade extends Model implements SchoolDirectoryInterface
{
    use HasFactory, HasSchoolDirectoryRelationships;
}
