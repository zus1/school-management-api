<?php

namespace App\Repository;

use App\Constant\QuestionType;
use App\Models\Exam;
use App\Models\Question;
use App\Trait\CanActivateModel;
use Illuminate\Database\Eloquent\Collection;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class QuestionRepository extends LaravelBaseRepository
{
    use CanActivateModel;

    protected const MODEL = Question::class;

    public function __construct(
        private ExamRepository $examRepository,
        private AnswerRepository $answerRepository,
    ){
    }

    public function creatBulk(array $questions, Exam $exam): Collection
    {
        $input = [];
        $totalPoints = 0;
        foreach ($questions as $questionArr) {
            $input[] = $this->make($questionArr);
            $totalPoints += $questionArr['points'];
        }

        $this->examRepository->addQuestions($exam, $input, $totalPoints);
        $this->addAnswers($questions);

        return new Collection($input);
    }

    public function update(array $data, Question $question): Question
    {
        $question->question = $data['question'];

        if($question->type !== $data['type']) {
            $this->changeType($question, $data['type']);
        }

        $question->save();

        return $question;
    }

    public function findByExamResponseIds(array $examResponseIds): Collection
    {
        $builder = $this->getBuilder();

        foreach ($examResponseIds as $examResponseId) {
            $builder->orWhereRelation('examResponses', 'id', $examResponseId);
        }

        return $builder->get();
    }

    private function addAnswers(array $questions): void
    {
        /** @var Question $question */
        foreach ($questions as $question) {
            if($question->type !== QuestionType::MULTIPLE_CHOICE) {
                continue;
            }

            $answers = $this->answerRepository->makeBulk($question['answers'] ?? []);

            $question->answers()->saveMany($answers->all());
        }
    }

    private function changeType(Question $question, string $type): void
    {
        if($question->type === QuestionType::MULTIPLE_CHOICE) {
            //@TODO delete associated answers
        }

        $question->type = $type;
    }

    public function changeExam(Question $question, Exam $exam): Question
    {
        /** @var Exam $currentEXam */
        $currentEXam = $question->exam()->first();

        $this->examRepository->decreaseTotalPoints($currentEXam, $question->points);
        $this->examRepository->increaseTotalPoints($exam, $question->points);

        $question->exam()->associate($exam);

        $question->save();

        return $question;
    }

    private function make(array $data): Question
    {
        $question = new Question();
        $question->points = $data['points'];
        $question->question = $data['question'];
        $question->type = $data['type'];
        $question->active = true;

        return $question;
    }
}
