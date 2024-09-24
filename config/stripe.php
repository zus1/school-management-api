<?php

return [
    'key' => env('STRIPE_KEY', ''),
    'secret' => env('STRIPE_SECRET', ''),
    'payment_webhook_secret' => env('STRIPE_PAYMENT_WEBHOOK_SECRET'),
    'product_webhook_secret' => env('STRIP_PRODUCT_WEBHOOK_SECRET'),

    'success_url' => env('STRIPE_PAYMENT_SUCCESS_URL'),
    'cancel_url' => env('STRIPE_PAYMENT_CANCEL_URL'),

    'products' => [
        'tuition' => [
            'id' => env('STRIPE_TUITION_PRODUCT_ID'),
        ],
    ],
];
