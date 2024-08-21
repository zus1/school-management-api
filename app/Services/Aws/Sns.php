<?php

namespace App\Services\Aws;

use Aws\Sns\SnsClient;

class Sns
{
    public function __construct(
        private SnsClient $client,
    ){
    }

    public function send(string $phone, string $message): bool
    {
        $result = $this->client->publish([
            'Message' => $message,
            'PhoneNumber' => $phone
        ]);

        return isset($result['MessageId']) && $result['MessageId'] !== '';
    }
}
