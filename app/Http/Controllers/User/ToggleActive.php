<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class ToggleActive
{
    public function __construct(
        private UserRepository $repository,
    ){
    }

    public function __invoke(Request $request, User $user, string $active): JsonResponse
    {
        $active = $active === 'true';

        $user = $this->repository->toggleActive($user, $active);

        return new JsonResponse(Serializer::normalize($user, 'user:toggleActive'));
    }
}
