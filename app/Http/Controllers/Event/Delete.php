<?php

namespace App\Http\Controllers\Event;

use App\Models\Event;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Event $event): JsonResponse
    {
        $event->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
