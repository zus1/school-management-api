<?php

namespace App\Services\Stripe;


use App\Constant\Payment\PaymentFlowStatus;
use App\Constant\Payment\PaymentStatus;
use App\Interface\WebhookProcessorInterface;
use App\Models\Payment;
use App\Repository\InvoiceRepository;
use App\Repository\PaymentRepository;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Invoice;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentEventsProcessor implements WebhookProcessorInterface
{
    private const PAYMENT_FAILED = 'checkout.session.async_payment_failed';
    private const PAYMENT_SUCCEEDED = 'checkout.session.async_payment_succeeded';
    private const SESSION_EXPIRED = 'checkout.session.expired';
    private const INVOICE_FINALIZED = 'invoice.finalized';


    public function __construct(
        private PaymentRepository $repository,
        private InvoiceRepository $invoiceRepository,
    ){
    }

    public function process(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent($payload, $signature, config('stripe.payment_webhook_secret'));
        $object = $event->data->object;

        try {
            if($event->type === self::PAYMENT_FAILED && $object instanceof Session) {
                $this->processFailedPayment($object);
            }
            if($event->type === self::PAYMENT_SUCCEEDED && $object instanceof Session) {
                $this->processSucceedPayment($object);
            }
            if($event->type === self::SESSION_EXPIRED && $object instanceof Session) {
                $this->processExpired($object);
            }
            if($event->type === self::INVOICE_FINALIZED && $object instanceof Invoice) {
                $this->processInvoiceFinalized($object);
            }
        } catch(\Exception $e) {
            Log::channel('payment')->error($e->getMessage() ,$object->toArray());
        }
    }

    public function processSucceedPayment(Session $session): void
    {
        $dbPayment = $this->fetchOngoingPayment($session->id);

        $this->repository->updateOnSuccess($dbPayment, $session, pendingInvoice: true);
    }

    private function processFailedPayment(Session $session): void
    {
        $dbPayment = $this->fetchOngoingPayment($session->id);

        $this->repository->updateOnFailed($dbPayment, $session);
    }

    private function processExpired(Session $session): void
    {
        $dbPayment = $this->fetchOngoingPayment($session->id);

        $this->repository->updateOnExpire($dbPayment, $session);
    }

    private function processInvoiceFinalized(Invoice $invoice): void
    {
        $dbPayment = $this->repository->findByPaymentIdOr404($invoice->id);

        if($dbPayment->payment_status !== PaymentStatus::PAID) {
            throw new HttpException(500, 'Payment is not paid, can\'t generate invoice '.$dbPayment->id);
        }
        if($dbPayment->flow_status !== PaymentFlowStatus::PENDING_INVOICE) {
            return;
        }

        $this->invoiceRepository->create($invoice, $dbPayment);
        $this->repository->updateOnInvoiceFinalized($dbPayment);
    }

    private function fetchOngoingPayment(string $id): Payment
    {

        $dbPayment = $this->repository->findBySessionIdOr404($id);

        if(PaymentFlowStatus::ongoing($dbPayment->flow_status) === false) {
            throw new HttpException(500, 'Payment is not ongoing '.$dbPayment->id);
        }

        return $dbPayment;
    }
}
