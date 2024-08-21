<?php

namespace App\Http\Controllers\Auth;

use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Zus1\Serializer\Facade\Serializer;

class VerifyPhone
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenRepository $tokenRepository,
    ){
    }

    public function __invoke(Request $request): JsonResponse
    {
        $code = $request->query('token');
        $toke = $this->tokenRepository->retrieve($code);

        $user = $this->userRepository->findByToken($toke);
        $this->userRepository->verifyPhone($user);

        $this->tokenRepository->deactivate($toke);

        return new JsonResponse(Serializer::normalize($user, 'user:verifyPhone'));
    }
}
