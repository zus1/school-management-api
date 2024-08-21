<?php

namespace App\Listeners;

use App\Constant\CustomTokenType;
use App\Models\User;
use App\Services\Aws\Sns;
use Zus1\LaravelAuth\Events\UserVerifiedEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;

class PhoneVerificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private Sns $sns,
        private TokenRepository $tokenRepository,
    ){
    }

    /**
     * Handle the event.
     */
    public function handle(UserVerifiedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->sns->send(
            phone: $user->phone,
            message: $this->makeMessage($user)
        );
    }

    private function makeMessage(User $user): string
    {
        $code = $this->tokenRepository->create($user, CustomTokenType::PHONE_VERIFICATION);

        return sprintf('Your verification code is: %s', $code->token);
    }
}
