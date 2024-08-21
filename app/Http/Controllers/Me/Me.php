<?php

namespace App\Http\Controllers\Me;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Zus1\Serializer\Facade\Serializer;

class Me
{
    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return new JsonResponse(Serializer::normalize($user, ['user:me', 'media:nestedUserMe']));
    }
}
