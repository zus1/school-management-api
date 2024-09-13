<?php

namespace App\Models;

use App\Interface\CanBeActiveInterface;
use App\Interface\MediasOwnerInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $question
 * @property int $points
 * @property string $type
 * @property int $active
 * @property int $exam_id
 */
#[Attributes([
    ['id',
        'question:createBulk', 'question:changeExam', 'question:collection', 'question:nestedAnswerUpdate',
        'question:nestedAnswerChangQuestion', 'mediaOwner:nestedMediaUpload', 'question:nestedExamResponseCreate',
        'question:nestedExamCollection',
    ],
    ['question',
        'question:createBulk', 'question:update', 'question:changeExam', 'question:collection',
        'question:nestedAnswerUpdate', 'question:nestedAnswerChangQuestion', 'mediaOwner:nestedMediaUpload',
        'question:nestedExamResponseCreate'
    ],
    ['points', 'question:createBulk', 'question:update', 'question:collection'],
    ['type', 'question:createBulk', 'question:update', 'question:nestedAnswerChangQuestion'],
    ['active', 'question:createBulk'],
    ['exam', 'question:changeExam'],
    ['answers', 'question:createBulk', 'question:retrieve'],
    ['medias', 'question:retrieve']
])]
class Question extends Model implements CanBeActiveInterface, MediasOwnerInterface
{
    use HasFactory;

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'media_owner');
    }

    public function mediaOwnerId(): int
    {
        return $this->id;
    }

    public function examResponses(): HasMany
    {
        return $this->hasMany(ExamResponse::class, 'question_id', 'id');
    }
}
