<?php

namespace App\Console\Commands;

use App\Constant\ExamSessionStatus;
use App\Models\ExamSession;
use App\Repository\ExamSessionRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class FinishExpiredExamSessions extends Command
{
    private const CHINK_SIZE = 100;

    public function __construct(
        private ExamSessionRepository $repository,
    ){
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:finish-expired-exam-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finishes exam session that are expired by changing their status to pending grade';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->repository->handleUnfinishedAndExpired(self::CHINK_SIZE, [$this, 'finishExpiredSessions']);

        return 0;
    }

    public function finishExpiredSessions(Collection $examSessions): void
    {
        $finishedSessions = [];

        $examSessions->each(function (ExamSession $examSession) use (&$finishedSessions) {
            $examSession->status = ExamSessionStatus::PENDING_GRADE;
            $examSession->ended_at = Carbon::now()->format('Y-m-d H:i:s');

            $finishedSessions[] = $examSession;
        });

        ExamSession::massUpdate($finishedSessions);
    }
}
