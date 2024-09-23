<?php

namespace App\Interface;

interface WebhookProcessorInterface
{
    public function process(string $payload, string $signature): void;
}
