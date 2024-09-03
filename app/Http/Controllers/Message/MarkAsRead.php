<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\MessageRequest;
use App\Repository\MessageRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class MarkAsRead
{
    public function __construct(
        private MessageRepository $repository,
    ){
    }

    public function __invoke(MessageRequest $request): JsonResponse
    {
        $marked = $this->repository->bulkMarkAsRead($request->input('message_ids'));

        return new JsonResponse(Serializer::normalize($marked, 'message:markAsRead'));
    }
}
