<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property int $lower
 * @property int $upper
 * @property int $grade
 * @property int $grading_rule_id
 */
#[Attributes([
    ['id', 'gradeRange:nestedGradingRuleRetrieve', 'gradeRange:create'],
    ['lower', 'gradeRange:nestedGradingRuleRetrieve', 'gradeRange:create', 'gradeRange:update'],
    ['upper', 'gradeRange:nestedGradingRuleRetrieve', 'gradeRange:create', 'gradeRange:update'],
    ['grade', 'gradeRange:nestedGradingRuleRetrieve', 'gradeRange:create', 'gradeRange:update'],
    ['gradingRule', 'gradeRange:create', 'gradeRange:update']
])]
class GradeRange extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function gradingRule(): BelongsTo
    {
        return $this->belongsTo(GradingRule::class, 'grading_rule_id', 'id');
    }

    public function teacher(): HasOneThrough
    {
        return $this->hasOneThrough(
            Teacher::class,
            GradingRule::class,
            'id',
            'id',
            'grading_rule_id',
            'teacher_id',
        );
    }
}
