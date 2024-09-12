<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $response
 * @property int $answer_id
 * @property bool $is_correct
 * @property string $comment
 * @property int $exam_session_id
 * @property int $question_id
 */
#[Attributes([
    ['id', 'examResponse:create', 'examResponse:collection', 'examResponse:retrieve'],
    ['response', 'examResponse:create', 'examResponse:retrieve', 'examResponse:update'],
    ['answer', 'examResponse:create', 'examResponse:retrieve', 'examResponse:update'],
    ['is_correct', 'examResponse:retrieve'],
    ['comment', 'examResponse:retrieve'],
    ['question', 'examResponse:create', 'examResponse:collection', 'examResponse:collection'],
])]
class ExamResponse extends Model
{
    use HasFactory;

    public function casts(): array
    {
        return [
            'is_correct' => 'boolean'
        ];
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id', 'id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }

    public function student(): HasOneThrough
    {
        return $this->hasOneThrough(
            Student::class,
            ExamSession::class,
            'id',
            'id',
            'exam_session_id',
            'student_id'
        );
    }

    public function exam(): HasOneThrough
    {
        return $this->hasOneThrough(
            Exam::class,
            ExamSession::class,
            'id',
            'id',
            'exam_session_id',
            'exam_id'
        );
    }
}
