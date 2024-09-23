<?php

namespace App\Http\Controllers\Payment;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Payment $payment): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($payment, [
            'payment:retrieve',
            'user:nestedPaymentRetrieve',
            'product:nestedPaymentRetrieve',
            'invoice:nestedPaymentRetrieve'
        ]));
    }
}
