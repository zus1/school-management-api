<?php

namespace App\Http\Controllers\Payment;

use App\Models\Guardian;
use App\Models\Product;
use App\Services\Stripe\Checkout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class Create
{
    public function __construct(
        private Checkout $checkout,
    ){
    }

    public function __invoke(Product $product): JsonResponse
    {
        /** @var Guardian $auth */
        $auth = Auth::user();

        $checkoutUrl = $this->checkout->create($auth, $product);

        return new JsonResponse([
            'checkout_url' => $checkoutUrl,
        ]);
    }
}
