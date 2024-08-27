<?php

namespace App\Http\Controllers\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class ToggleNotify
{
    public function __invoke(Event $event, User $user, string $action): JsonResponse
    {
        if($action === 'add') {
            $event->notifyUsers()->attach($user->id);
        }
        if($action === 'remove') {
            $event->notifyUsers()->detach($user->id);
        }

        return new JsonResponse(Serializer::normalize($event, ['event:toggleNotify', 'user:nestedEventToggleNotify']));
    }
}
