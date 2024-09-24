<?php

namespace App\Listeners;

use App\Events\PaymentOccurred;
use App\Models\Guardian;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Student;
use App\Repository\TuitionRepository;
use App\Repository\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaymentOccurredListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private TuitionRepository $tuitionRepository,
        private UserRepository $userRepository,
    ){
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentOccurred $event): void
    {
        $payment = $event->getPayment();

        /** @var Product $product */
        $product = $payment->product()->first();

        if($product->product_id === config('stripe.products.tuition.id')) {
            $this->handleTuitionPayment($payment);
        }
    }

    private function handleTuitionPayment(Payment $payment): void
    {
        $this->tuitionRepository->paid($payment);

        $this->reactivateStudents($payment);
    }

    private function reactivateStudents(Payment $payment): void
    {
        /** @var Guardian $guardian */
        $guardian = $this->userRepository->findChildByParent($payment->user()->first());

        $reactivated = [];
        /** @var Student $student */
        foreach ($guardian->students() as $student) {
            if($student->active === false) {
                $student->active = true;

                $reactivated[] = $student;
            }
        }

        if($reactivated !== []) {
            Student::massUpdate($reactivated);
        }
    }
}
