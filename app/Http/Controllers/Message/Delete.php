<?php

namespace App\Http\Controllers\Message;

use App\Models\Message;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Message $message): JsonResponse
    {
        $message->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
