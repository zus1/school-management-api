<?php

namespace App\Dto;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;
use Zus1\Serializer\Facade\Serializer;

class QuestionsCreateResponse implements \JsonSerializable
{
    private array $exam;
    private array $questions;

    public static function create(Collection $questions, Exam $exam): self
    {
        $instance = new self();
        $instance->exam = Serializer::normalize($exam, 'exam:nestedQuestionsCreate');
        $instance->questions = Serializer::normalize($questions, ['question:createBulk', 'answer:nestedQuestionCreateBulk']);

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
