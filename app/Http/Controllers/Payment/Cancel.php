<?php

namespace App\Http\Controllers\Payment;

use App\Services\Stripe\Checkout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Cancel
{
    public function __construct(
        private Checkout $checkout,
    ){
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payment = $this->checkout->onCancel($request->query('session_id'));

        return new JsonResponse(Serializer::normalize($payment, 'payment:cancel'));
    }
}
