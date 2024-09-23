<?php

namespace App\Repository;

use App\Constant\Payment\PaymentFlowStatus;
use App\Constant\Payment\PaymentStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class PaymentRepository extends LaravelBaseRepository
{
    protected const MODEL = Payment::class;

    public function create(Session $session, User $user, Product $product): Payment
    {
        $payment = new Payment();
        $payment->created_at = Carbon::now()->setTimestamp($session->created)->format('Y-m-d H:i:s');
        $payment->checkout_id = $session->id;
        $payment->payment_id = $session->payment_intent?->id;
        $payment->payment_status = $session->payment_status;
        $payment->flow_status = PaymentFlowStatus::IN_PROGRESS;
        $payment->currency = $session->currency;

        $this->addAmounts($payment, $session);

        $payment->user()->associate($user);
        $payment->product()->associate($product);

        $payment->save();

        return $payment;
    }

    public function findBySessionIdOr404(?string $sessionId): Payment
    {
        if($sessionId === null) {
            throw new HttpException(400, 'Session id not provided');
        }

        /** @var Payment $payment */
        $payment = $this->findOneByOr404(['checkout_id' => $sessionId]);

        return $payment;
    }

    public function findByPaymentIdOr404(?string $paymentId): Payment
    {
        if($paymentId === null) {
            throw new HttpException(400, 'Payment id id not provided');
        }

        /** @var Payment $payment */
        $payment = $this->findOneByOr404(['payment_id' => $paymentId]);

        return $payment;
    }

    public function updateOnSuccess(Payment $payment, Session $session, bool $pendingInvoice): Payment
    {
        $payment->payment_id = $session->payment_intent;
        $payment->payment_status = $session->payment_status;
        $payment->flow_status = $pendingInvoice === true ? PaymentFlowStatus::PENDING_INVOICE : PaymentFlowStatus::FINISHED;

        $this->addTotalDetails($payment, $session);

        $payment->save();

        return $payment;
    }

    public function updateOnCancel(Payment $payment, Session $session): Payment
    {
        $payment->flow_status = PaymentFlowStatus::CANCELED;
        $payment->checkout_url = $session->url;

        $payment->save();

        return $payment;
    }

    public function updateOnFailed(Payment $payment, Session $session): Payment
    {
        $payment->payment_status = PaymentStatus::FAILED;
        $payment->flow_status = PaymentFlowStatus::FINISHED;
        $payment->payment_id = $session->payment_intent;

        $payment->save();

        return $payment;
    }

    public function updateOnExpire(Payment $payment, Session $session): Payment
    {
        $payment->flow_status = $session->status;
        $payment->payment_status = PaymentStatus::FAILED;

        $payment->save();

        return $payment;
    }

    public function updateOnInvoiceFinalized(Payment $payment): Payment
    {
        $payment->flow_status = PaymentFlowStatus::FINISHED;

        $payment->save();

        return $payment;
    }

    private function addAmounts(Payment $payment, Session $session): void
    {
        $payment->sub_total = $session->amount_subtotal;
        $payment->total = $session->amount_total;

        $this->addTotalDetails($payment, $session);
    }

    private function addTotalDetails(Payment $payment, Session $session): void
    {
        $totalDetails = $session->total_details ?? [];

        if($totalDetails !== []) {
            $payment->tax = $totalDetails['amount_tax'] ?? 0;
            $payment->discount = $totalDetails['amount_discount'] ?? 0;
        }
    }
}
