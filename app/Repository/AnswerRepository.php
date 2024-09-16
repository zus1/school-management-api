<?php

namespace App\Repository;

use App\Constant\QuestionType;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class AnswerRepository extends LaravelBaseRepository
{
    protected const MODEL = Answer::class;

    public function makeBulk(array $answers): Collection
    {
        $answerModels = [];
        foreach ($answers as $answerArr) {
            $answerModels[] = $this->make($answerArr);
        }

        return new Collection($answerModels);
    }

    public function update(array $data, Answer $answer): Answer
    {
        $this->modifySharedData($answer, $data);

        $answer->save();

        return $answer;
    }

    public function changeQuestion(Answer $answer, Question $newQuestion): Answer
    {
        if($newQuestion->type !== QuestionType::MULTIPLE_CHOICE) {
            throw new HttpException(
                400,
                'Can not assign answer to question that\'s not of type '.QuestionType::MULTIPLE_CHOICE
            );
        }

        $answer->question()->associate($newQuestion);

        $answer->save();

        return $answer;
    }

    private function make(array $data): Answer
    {
        $answer = new Answer();

        $this->modifySharedData($answer, $data);

        return $answer;
    }

    private function modifySharedData(Answer $answer, array $data): void
    {
        $answer->answer = $data['answer'];
        $answer->position = $data['position'] ?? null;
    }
}
