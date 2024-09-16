<?php

namespace App\Repository;

use App\Constant\ExamSessionStatus;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ExamSessionRepository extends LaravelBaseRepository
{
    protected const MODEL = ExamRepository::class;

    public function create(Exam $exam): ExamSession
    {
        $this->checkIfExamIsStarted($exam);

        $startedAt = Carbon::now();

        $examSession = new ExamSession();
        $examSession->started_at = $startedAt;
        $examSession->ends_at = $startedAt->addMinutes($exam->duration);
        $examSession->status = ExamSessionStatus::IN_PROGRESS;

        $this->associateStudent($examSession);
        $examSession->exam()->associate($exam);

        $examSession->save();

        return $examSession;
    }

    public function delete(ExamSession $examSession): void
    {
        if($examSession->status === ExamSessionStatus::IN_PROGRESS) {
            throw new HttpException(400, 'Can not delete session that is in progress');
        }

        $examSession->delete();
    }

    public function finish(ExamSession $examSession): ExamSession
    {
        if($examSession->status !== ExamSessionStatus::IN_PROGRESS) {
            throw new HttpException(400, 'Session already finished');
        }

        $endedAt = Carbon::now();
        $startedAt = new Carbon($examSession->started_at);

        $examSession->ended_at = $endedAt->format('Y-m-d H:i:s');
        $examSession->duration = $startedAt->diffInMinutes($endedAt);
        $examSession->status = ExamSessionStatus::PENDING_GRADE;

        $examSession->save();

        return $examSession;
    }

    public function grade(ExamSession $examSession, Exam $exam, int $grade, int $achievedPoints, ?string $comment): ExamSession
    {
        $examSession->grade = $grade;
        $examSession->comment = $comment;
        $examSession->achieved_points = $achievedPoints;
        $examSession->achieved_percentage = $achievedPoints/$exam->total_points;
        $examSession->status = ExamSessionStatus::GRADED;

        $examSession->save();

        return $examSession;
    }

    public function handleUnfinishedAndExpired(int $chunkSize, array $callback): void
    {
        $builder = $this->getBuilder();

        $builder->where('status', ExamSessionStatus::IN_PROGRESS)
            ->where('ends_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->chunk($chunkSize, $callback);
    }

    private function checkIfExamIsStarted(Exam $exam): void
    {
        $now = Carbon::now();
        $examStartsAt = (new Carbon($exam->starts_at));

        if($now->format('Y-m-d H:i') < $examStartsAt->format('Y-m-d H:i')) {
            throw new HttpException(400, sprintf(
                'Exam did not started yet, starts in %d minutes',
                ceil($examStartsAt->diffInMinutes($now))
            ));
        }
    }

    private function associateStudent(ExamSession $examSession): void
    {
        /** @var Student $student */
        $student = Auth::user();

        $examSession->student()->associate($student);
    }
}
