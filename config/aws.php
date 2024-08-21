<?php

return [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),

    'sns' => [
        'version' => env('AWS_SNS_VERSION'),
    ],

    's3' => [
        'bucket' => env('AWS_S3_BUCKET'),
        'version' => env('AWS_S3_VERSION'),
        'url' => env('AWS_S3_URL'),
    ],
];
