<?php

namespace App\Models;

use App\Interface\CloneableInterface;
use App\Interface\HasSchoolClassInterface;
use App\Interface\HasTeacherInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Discriminator\Observers\DiscriminatorObserver;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $teacher_id
 * @property string $school_class_id
 * @property string $classroom_id
 */
#[Attributes([
    ['id', 'event:create', 'event:retrieve', 'event:collection'],
    ['parent', 'event:create', 'event:update', 'event:retrieve', 'event:collection'],
    ['teacher', 'event:create', 'event:update', 'event:retrieve'],
    ['schoolClass', 'event:create', 'event:update', 'event:retrieve'],
    ['classroom', 'event:create', 'event:update', 'event:retrieve'],
])]
#[ObservedBy(DiscriminatorObserver::class)]
class SubjectEvent extends Event implements HasTeacherInterface, HasSchoolClassInterface, CloneableInterface
{
    use HasFactory;

    public $timestamps = false;

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function clone(): self
    {
        $clone = new self();

        $clone->teacher_id = $this->teacher_id;
        $clone->school_class_id = $this->school_class_id;
        $clone->classroom_id = $this->classroom_id;
        $clone->setPreservedIdentifier($this->id);

        return $clone;
    }
}
