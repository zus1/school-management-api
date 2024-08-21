<?php

namespace App\Http\Controllers\Me;

use App\Constant\UserType;
use App\Http\Requests\MeRequest;
use App\Interface\CanUpdateUserInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __invoke(MeRequest $request): JsonResponse
    {
        /** @var CanUpdateUserInterface $repository */
        $repository = App::make(UserType::repositoryClass($request->query('user_type')));

        /** @var User $user */
        $user = Auth::user();
        $user = $repository->update($request->input(), $user);

        return new JsonResponse(Serializer::normalize($user, 'user:meUpdate'));
    }
}
