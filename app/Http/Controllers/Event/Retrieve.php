<?php

namespace App\Http\Controllers\Event;

use App\Constant\Calendar\CalendarEventType;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Request $request, Event $event): JsonResponse
    {
        $eventType = $request->query('event_type');
        $serializationGroups = CalendarEventType::serializationGroups($eventType)['retrieve'];

        return new JsonResponse(Serializer::normalize($event, $serializationGroups));
    }
}
