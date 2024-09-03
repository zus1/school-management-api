<?php

namespace App\Http\Controllers\Message;

use App\Models\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __construct(
        private UserRepository $userRepository,
        private MessageRepository $repository,
    ){
    }

    public function __invoke(Message $message): JsonResponse
    {
        $auth = $this->userRepository->findAuthParent();
        if($auth->id === $message->recipient_id) {
            $this->repository->markAsRead($message);
        }

        return new JsonResponse(Serializer::normalize($message, ['message:retrieve', 'user:nestedMessageRetrieve']));
    }
}
