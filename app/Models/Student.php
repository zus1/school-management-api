<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'student:collection', 'user:nestedEventToggleNotify', 'student:nestedGradeCreate', 'student:nestedGradeCollection'
    ],
    ['onboarded_at', 'student:onboard', 'student:retrieve'],
    ['last_change_at', 'student:update'],
    ['parent',
        'student:create', 'student:onboard', 'user:me', 'student:update', 'student:retrieve',
        'student:collection', 'user:nestedEventToggleNotify', 'student:nestedGradeCreate', 'student:nestedGradeCollection'
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
}
