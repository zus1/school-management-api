<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $answer
 * @property int $position
 * @property int $question_id
 */
#[Attributes([
    ['id',
        'answer:nestedQuestionCreateBulk', 'answer:nestedQuestionRetrieve', 'answer:changeQuestion',
        'answer:nextedExamResponseCreate'
    ],
    ['answer',
        'answer:nestedQuestionCreateBulk', 'answer:nestedQuestionRetrieve', 'answer:update',
        'answer:nextedExamResponseCreate', 'answer:nestedExamResponseRetrieve', 'answer:nestedExamUpdate'
    ],
    ['position', 'answer:nestedQuestionCreateBulk', 'answer:nestedQuestionRetrieve', 'answer:update'],
    ['question', 'answer:update', 'answer:changeQuestion']
])]
class Answer extends Model
{
    use HasFactory;

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function exam(): HasOneThrough
    {
        return $this->hasOneThrough(
            Exam::class,
            Question::class,
            'id',
            'id',
            'question_id',
            'exam_id',
        );
    }
}
