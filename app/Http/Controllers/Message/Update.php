<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Repository\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private MessageRepository $repository,
    ){
    }

    public function __invoke(MessageRequest $request, Message $message): JsonResponse
    {
        $message = $this->repository->update($request->input(), $message);

        return new JsonResponse(Serializer::normalize($message, 'message:update'));
    }
}
