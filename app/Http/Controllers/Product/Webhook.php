<?php

namespace App\Http\Controllers\Product;

use App\Services\Stripe\ProductEventProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Webhook
{
    public function __construct(
        private ProductEventProcessor $processor,
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
