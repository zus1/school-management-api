<?php

namespace App\Http\Controllers\Auth;

use App\Constant\UserType;
use App\Http\Requests\RegisterRequest;
use App\Interface\CanRegisterUserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Zus1\LaravelAuth\Constant\Token\TokenType;
use Zus1\LaravelAuth\Mail\Send;
use Zus1\LaravelAuth\Mail\VerificationEmail;
use Zus1\Serializer\Facade\Serializer;

class Register
{
    public function __construct(
        private Send $mailer,
    ){
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        /** @var CanRegisterUserInterface $repository */
        $repository = App::make(UserType::repositoryClass($request->query('type')));

        $user = $repository->register($request->input());

        $this->mailer->send($user, VerificationEmail::class, TokenType::USER_VERIFICATION);

        return new JsonResponse(Serializer::normalize($user, ['user:register']));
    }
}
