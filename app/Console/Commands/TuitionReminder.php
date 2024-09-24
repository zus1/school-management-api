<?php

namespace App\Console\Commands;

use App\Mail\TuitionReminderEmail;
use App\Models\Tuition;
use App\Models\User;
use App\Repository\TuitionRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class TuitionReminder extends Command
{
    private const CHUNK_SIZE = 2;

    public function __construct(
        private TuitionRepository $repository,
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tuition-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent email reminder to guardians about unpaid tuitions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->repository->handleUnpaidTuitionReminders(self::CHUNK_SIZE, [$this, 'sendReminders']);

        return 0;
    }

    public function sendReminders(Collection $tuitions): void
    {
        $updatedUsers = [];
        $updatedTuitions = [];

        /** @var Tuition $tuition */
        foreach ($tuitions as $tuition) {

            $this->deactivateOverdueStudent($tuition->student->parent, $tuition, $updatedUsers);

            $this->sendReminder($tuition, $updatedTuitions);
        }

        $this->flushUpdates($updatedTuitions, $updatedUsers);
    }

    private function deactivateOverdueStudent(User $user, Tuition $tuition, array &$updatedUsers): void
    {
        if($tuition->due_at <= Carbon::now()->format('Y-m-d') && $user->active === true) {
            $user->active = false;

            $updatedUsers[] = $user;
        }
    }

    private function sendReminder(Tuition $tuition, array &$updatedTuitions): void
    {
        Mail::to($tuition->guardian->email)->send(new TuitionReminderEmail($tuition->student, $tuition));

        $tuition->last_reminder_sent_at = Carbon::now()->format('Y-m-d');

        $updatedTuitions[] = $tuition;
    }

    private function flushUpdates(array $updatedTuitions, array $updatedUsers): void
    {
        Tuition::massUpdate($updatedTuitions);

        if($updatedUsers !== []) {
            User::massUpdate($updatedUsers);
        }
    }
}
