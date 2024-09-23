<?php

namespace App\Http\Controllers\Payment;

use App\Services\Stripe\PaymentEventsProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Webhook
{
    public function __construct(
        private PaymentEventsProcessor $processor,
    ){
    }

    public function __invoke(Request $request): Response
    {
        $this->processor->process(
            payload: $request->getContent(),
            signature: $request->header('stripe-signature')
        );

        return new Response('', Response::HTTP_OK);
    }
}
