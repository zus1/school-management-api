<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $teacher_id
 */
#[Attributes([
    ['id',
        'gradingRule:create', 'gradingRule:retrieve', 'gradingRule:collection', 'gradingRule:nestedGradeRangeCreate',
        'gradingRule:nestedGradeRangeUpdate'
    ],
    ['name',
        'gradingRule:create', 'gradingRule:update', 'gradingRule:collection', 'gradingRule:retrieve',
        'gradingRule:nestedGradeRangeCreate', 'gradingRule:nestedGradeRangeUpdate'
    ],
    ['description', 'gradingRule:create', 'gradingRule:retrieve', 'gradingRule:update'],
    ['ranges', 'gradingRule:retrieve']
])]
class GradingRule extends Model
{
    use HasFactory;

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function ranges(): HasMany
    {
        return $this->hasMany(GradeRange::class, 'grading_rule_id', 'id');
    }
}
