<?php

namespace App\Repository;

use App\Constant\Payment\PaymentStatus;
use App\Constant\TuitionStatus;
use App\Models\Guardian;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Tuition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class TuitionRepository extends LaravelBaseRepository
{
    protected const MODEL = Tuition::class;

    public function __construct(
        private UserRepository $userRepository,

    ){
    }

    public function paid(Payment $payment): void
    {
        /** @var Guardian $guardian */
        $guardian = $this->userRepository->findChildByParent($payment->user()->first());
        $lastPaid = (new Carbon($payment->created_at))->format('Y');

        $this->getBuilder()->where(DB::raw("DATE_FORMAT(created_at, '%Y')"), $lastPaid)
            ->whereRelation('guardian', 'id', $guardian->id)
            ->update([
                'payment_id' => $payment->id,
                'last_paid' => $lastPaid,
                'status' => TuitionStatus::PAID,
            ]);
    }

    public function createUnpaid(Student $student): Tuition
    {
        $tuition = new Tuition();
        $this->modifySharedData($tuition);

        $tuition->student()->associate($student);
        $tuition->guardian()->associate($student->guardian()->first());

        $tuition->save();

        return $tuition;
    }

    public function makeUnpaid(Student $student): Tuition
    {
        $tuition = new Tuition();
        $this->modifySharedData($tuition);

        $tuition->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $tuition->student_id = $student->id;
        $tuition->guardian_id = $student->guardian->id;

        return $tuition;
    }

    public function handleUnpaidTuitionReminders(int $chunkSize, array $callback): void
    {
        $builder = $this->getBuilder();

        $builder->where('status', TuitionStatus::UNPAID)
            ->where(function (Builder $builder) {
                $builder->where('last_reminder_sent_at', '<=', Carbon::now()->format('Y-m-d'))
                    ->orWhereNull('last_reminder_sent_at');
            })
            ->with('student', fn (BelongsTo $builder) => $builder->with('parent'))
            ->with('guardian')
            ->chunkById($chunkSize, $callback);
    }

    public function insert(Collection $tuitions): void
    {
        $this->getBuilder()->insert($tuitions->toArray());
    }

    private function modifySharedData(Tuition $tuition): void
    {
        $tuition->status = TuitionStatus::UNPAID;
        $tuition->due_at = Carbon::now()->addMonth()->format('Y-m-d');
    }
}
