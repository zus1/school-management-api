<?php

namespace App\Http\Controllers\Event;

use App\Constant\Calendar\CalendarEventType;
use App\Dto\EventCollectionResponseDto;
use App\Models\Calendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(
        private Request $request,
        private ?string $eventType = '',
    ){
        $eventType = $this->request->query('event_type');
        $repository = App::make(CalendarEventType::repository($eventType));

        $this->eventType = $eventType;

        parent::__construct($repository);
    }

    public function __invoke(Calendar $calendar): JsonResponse
    {
        $serializationGroups = CalendarEventType::serializationGroups($this->eventType)['collection'];

        $this->addCollectionRelation([
            'relation' => $this->eventType === null ? 'calendar' : 'parent',
            'field' => $this->eventType === null ? 'id' : 'calendar_id',
            'value' => $calendar->id
        ]);

        $collection = $this->retrieveCollection($this->request);

        return new JsonResponse(
            EventCollectionResponseDto::create($collection, $calendar, eventsSerializationGroups: $serializationGroups));
    }
}
