<?php

namespace App\Repository;

use App\Constant\ExamSessionStatus;
use App\Models\ExamResponse;
use App\Models\ExamSession;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ExamResponseRepository extends LaravelBaseRepository
{
    protected const MODEL = ExamResponse::class;

    public function __construct(
        private QuestionRepository $questionRepository,
        private AnswerRepository $answerRepository,
    ){
    }

    public function create(array $data, ExamSession $examSession): ExamResponse
    {
        $this->checkSessionStatus($examSession);

        $examResponse = new ExamResponse();
        $examResponse->response = $data['response'] ?? null;

        $examResponse->examSession()->associate($examSession);

        $this->associateQuestion($examResponse, $data['question_id']);

        $this->modifySharedData($examResponse, $data);

        $examResponse->save();

        return $examResponse;
    }

    public function update(array $data, ExamResponse $examResponse): ExamResponse
    {
        $this->checkSessionStatus($examResponse->examSession()->first());

        $examResponse->response = $data['response'];

        $this->modifySharedData($examResponse, $data);

        return $examResponse;
    }

    public function delete(ExamResponse $examResponse): void
    {
        /** @var ExamSession $examSession */
        $examSession = $examResponse->examSession()->first();

        if($examSession->status === ExamSessionStatus::IN_PROGRESS) {
            throw new HttpException(400, 'Can not delete response that belongs to session that\'s still in progress');
        }

        $examResponse->delete();
    }

    public function findByIds(array $ids): Collection
    {
        $builder = $this->getBuilder();

        return $builder->whereIn('id', $ids)->get();
    }

    private function modifySharedData(ExamResponse $examResponse, array $data): void
    {
        if(isset($data['answer_id'])) {
            $this->associateAnswer($examResponse, $data['answer_id']);
        }
    }

    private function associateQuestion(ExamResponse $examResponse, int $questionId): void
    {
        $question = $this->questionRepository->findOneByOr404(['id' => $questionId]);
        $examResponse->question()->associate($question);
    }

    private function associateAnswer(ExamResponse $examResponse, int $answerId): void
    {
        $answer = $this->answerRepository->findOneByOr404(['id' => $answerId]);
        $examResponse->answer()->associate($answer);
    }

    private function checkSessionStatus(ExamSession $examSession): void
    {
        if($examSession->status !== ExamSessionStatus::IN_PROGRESS) {
            throw new HttpException(400, 'Can only add response to session that are in progress');
        }
    }
}
