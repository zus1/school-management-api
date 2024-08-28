<?php

namespace App\Http\Controllers\Event;

use App\Constant\Calendar\CalendarEventType;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private EventRequest $request,
    ){
    }

    public function __invoke(Event $event): JsonResponse
    {
        $eventType = $this->request->query('event_type');
        $repository = App::make(CalendarEventType::repository($eventType));
        $serializationGroups = CalendarEventType::serializationGroups($eventType)['update'];

        $event = $repository->update($this->request->input(), $event);

        return new JsonResponse(Serializer::normalize($event, $serializationGroups));
    }
}
