<?php

namespace App\Services\Stripe;

use App\Constant\Payment\PaymentStatus;
use App\Events\PaymentOccurred;
use App\Models\Guardian;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Repository\InvoiceRepository;
use App\Repository\PaymentRepository;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class Checkout
{
    public function __construct(
        private StripeClient $client,
        private PaymentRepository $repository,
        private InvoiceRepository $invoiceRepository,
    ){
    }

    public function create(User $user, Product $product): string
    {
        $session = $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => [
                [
                    'price' => $product->price_id,
                    'quantity' => $this->addQuantity($user),
                ]
            ],
            'success_url' => sprintf('%s?session_id={CHECKOUT_SESSION_ID}', config('stripe.success_url')),
            'cancel_url' => sprintf('%s?session_id={CHECKOUT_SESSION_ID}', config('stripe.cancel_url')),
            'invoice_creation' => [
                'enabled' => true,
            ],
            'tax_id_collection' => [
                'enabled' => true,
            ],
            'automatic_tax' => [
                'enabled' => true,
            ],
            'billing_address_collection' => 'required',
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
        ]);


        $this->repository->create($session, $user->parent()->first(), $product);

        return $session->url;
    }

    private function addQuantity(User $user)
    {
        if($user instanceof Guardian) {
            return $user->students()->count();
        }

        return 1;
    }

    public function onSuccess(string $sessionId): Payment
    {
        $session = $this->client->checkout->sessions->retrieve($sessionId);
        $dbPayment = $this->repository->findBySessionIdOr404($sessionId);

        if($session->payment_status !== PaymentStatus::PAID) {
            return $dbPayment;
        }

        $dbPayment = $this->repository->updateOnSuccess($dbPayment, $session, pendingInvoice: $session->invoice === null);

        $this->handleInvoice($session, $dbPayment);

        PaymentOccurred::dispatch($dbPayment);

        return $dbPayment;
    }

    public function onCancel(string $sessionId): Payment
    {
        $session = $this->client->checkout->sessions->retrieve($sessionId);
        $dbPayment = $this->repository->findBySessionIdOr404($sessionId);

        return $this->repository->updateOnCancel($dbPayment, $session);
    }

    private function handleInvoice(Session $session, Payment $dbPayment): void
    {
        if($session->invoice === null) {
            return;
        }

        $invoice = $this->client->invoices->retrieve($session->invoice);
        $this->invoiceRepository->create($invoice, $dbPayment);
    }
}
