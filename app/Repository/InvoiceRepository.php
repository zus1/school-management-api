<?php

namespace App\Repository;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class InvoiceRepository extends LaravelBaseRepository
{
    protected const MODEL = Invoice::class;

    public function create(\Stripe\Invoice $stripeInvoice, Payment $payment): Invoice
    {
        $invoice = new Invoice();
        $invoice->invoice_id = $stripeInvoice->id;
        $invoice->created_at = Carbon::now()->setTimestamp($stripeInvoice->created)->format('Y-m-d H:i:s');
        $invoice->url = $stripeInvoice->hosted_invoice_url;
        $invoice->pdf_url = $stripeInvoice->invoice_pdf;

        $invoice->payment()->associate($payment);

        $invoice->save();

        return $invoice;
    }
}
