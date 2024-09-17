<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $starts_at
 * @property string $ends_at
 * @property int $duration
 * @property int $total_points
 * @property int $teacher_id
 * @property int $subject_id
 * @property int $grading_rule_id
 * @property array $school_classes_allowed_access
 */
#[Attributes([
    ['id',
        'exam:create', 'exam:retrieve', 'exam:toggleGradingRule', 'exam:nestedQuestionsCreate',
        'exam:nestedQuestionChangeExam'. 'exam:toggleAllowedSchoolClass', 'exam:nestedExamSessionCreate',
        'exam:nestedExamSessionRetrieve'
    ],
    ['title',
        'exam:create', 'exam:update', 'exam:retrieve', 'exam:toggleGradingRule', 'exam:nestedQuestionsCreate',
        'exam:nestedQuestionChangeExam', 'exam:toggleAllowedSchoolClass', 'exam:nestedExamSessionCreate',
        'exam:nestedExamSessionRetrieve'
    ],
    ['starts_at', 'exam:create', 'exam:update', 'exam:retrieve'],
    ['ends_at', 'exam:create', 'exam:update', 'exam:retrieve'],
    ['duration', 'exam:create', 'exam:update', 'exam:retrieve'],
    ['total_points', 'exam:create', 'exam:retrieve', 'exam:nestedQuestionsCreate', 'exam:nestedQuestionChangeExam'],
    ['description', 'exam:create', 'exam:update', 'exam:retrieve'],
    ['subject', 'exam:create', 'exam:update', 'exam:retrieve'],
    ['gradingRule', 'exam:create', 'exam:retrieve', 'exam:toggleGradingRule'],
    ['school_classes_allowed_access', 'exam:toggleAllowedSchoolClass'],
    ['teacher', 'exam:retrieve'],
])]
class Exam extends Model
{
    use HasFactory;

    public function casts(): array
    {
        return [
            'school_classes_allowed_access' => 'array',
        ];
    }

    public function startsAt(): Attribute
    {
        return Attribute::get(fn (string $value) => (new Carbon($value))->toDateTimeString());
    }

    public function endsAt(): Attribute
    {
        return Attribute::get(fn (string $value) => (new Carbon($value))->toDateTimeString());
    }

    public function teacher(): BelongsTo
    {
        return  $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function gradingRule(): BelongsTo
    {
        return $this->belongsTo(GradingRule::class, 'grading_rule_id', 'id');
    }

    public function gradeRanges(): HasManyThrough
    {
        return $this->hasManyThrough(
            GradeRange::class,
            GradingRule::class,
            'id',
            'grading_rule_id',
            'grading_rule_id',
            'id',
        );
    }

    public function maxGradeRange(): ?int
    {
        /** @var ?GradeRange $gradeRange */
        $gradeRange = $this->gradeRanges()->orderBy('upper', 'DESC')->first();

        if($gradeRange === null) {
            return null;
        }

        return $gradeRange->upper;
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'exam_id', 'id');
    }

    public function isSchoolClassAllowed(string $schoolClass):  bool
    {
        return in_array($schoolClass, $this->school_classes_allowed_access ?? []);
    }
}
