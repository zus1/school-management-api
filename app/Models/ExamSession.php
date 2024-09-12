<?php

namespace App\Models;

use App\Trait\Mutator\DateTimeMutator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $started_at
 * @property string $ends_at
 * @property string $ended_at
 * @property int $achieved_points
 * @property float $achieved_percentage
 * @property int $grade
 * @property string $comment
 * @property string $status
 * @property int $duration
 * @property int $student_id
 */
#[Attributes([
    ['id', 'examSession:create', 'examSession:collection', 'examSession:retrieve'],
    ['started_at', 'examSession:create', 'examSession:finish', 'examSession:collection', 'examSession:retrieve'],
    ['ends_at', 'examSession:create', 'examSession:collection', 'examSession:retrieve'],
    ['ended_at', 'examSession:finish', 'examSession:collection', 'examSession:retrieve'],
    ['status', 'examSession:create', 'examSession:finish', 'examSession:collection', 'examSession:retrieve'],
    ['exam', 'examSession:create', 'examSession:retrieve'],
    ['student', 'examSession:collection', 'examSession:retrieve']
])]
class ExamSession extends Model
{
    use HasFactory, DateTimeMutator;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function startedAt(): Attribute
    {
        return $this->mutateDateTime();
    }

    public function endsAt(): Attribute
    {
        return $this->mutateDateTime();
    }

    public function endedAt(): Attribute
    {
        return $this->mutateDateTime();
    }
}
