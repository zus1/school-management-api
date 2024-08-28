<?php

namespace App\Http\Controllers\Event;

use App\Constant\Calendar\CalendarEventType;
use App\Http\Requests\EventRequest;
use App\Models\Calendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private EventRequest $request,
    ){
    }

    public function __invoke(Calendar $calendar): JsonResponse
    {
        $eventType = $this->request->query('event_type');
        $repository = App::make(CalendarEventType::repository($eventType));
        $serializationGroups = CalendarEventType::serializationGroups($eventType)['create'];

        $event = $repository->create($this->request->input(), $calendar);

        return new JsonResponse(Serializer::normalize($event, $serializationGroups));
    }
}
