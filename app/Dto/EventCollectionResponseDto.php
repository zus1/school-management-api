<?php

namespace App\Dto;

use App\Models\Calendar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Zus1\Serializer\Facade\Serializer;

class EventCollectionResponseDto implements \JsonSerializable
{
    private array $calendar;
    private array $events;

    public static function create(LengthAwarePaginator $events, Calendar $calendar, array $eventsSerializationGroups): self
    {
        $instance = new self();
        $instance->events = Serializer::normalize(new Collection($events->all()), $eventsSerializationGroups);
        $instance->calendar = Serializer::normalize($calendar, 'calendar:nestedEventCollection');

        return $instance;
    }

    private function __construct(){
    }


    public function jsonSerialize(): array
    {
        return [
            ...$this->calendar,
            'events' => $this->events,
        ];
    }
}
