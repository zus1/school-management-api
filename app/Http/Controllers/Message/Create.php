<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\MessageRequest;
use App\Models\User;
use App\Repository\MessageRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private MessageRepository $repository,
    ){
    }

    public function __invoke(MessageRequest $request, User $recipient): JsonResponse
    {
        $message = $this->repository->create($request->input(), $recipient);

        return new JsonResponse(Serializer::normalize($message, ['message:create', 'user:nestedMessageCreate']));
    }
}
