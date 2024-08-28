<?php

namespace App\Console\Commands;

use App\Constant\Calendar\CalendarEventRepeat;
use App\Constant\Calendar\CalendarEventType;
use App\Models\Event;
use App\Repository\EventRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

class RescheduleRepeatableEvents extends Command
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private EventRepository $repository
    ){
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar-event:reschedule-repeatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reschedules repeatable events';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $eligibleEvents = $this->repository->findForReschedule();

        $clones = new Collection();
        $children = [];
        /** @var Event $event */
        foreach ($eligibleEvents as $event) {
            $rescheduledEvent = $event->clone();

            $this->setTimeInterval($rescheduledEvent);

            $this->handleChild($rescheduledEvent, $children);

            $clones->add($rescheduledEvent);
        }

        $insertedClones = $this->repository->insertRescheduled($clones);
        $insertedChildren = [];
        $this->handleInsertedChildren($children, $insertedChildren);

        $toUpdate = [];
        $this->handleAssignChildren($insertedClones, $insertedChildren, $toUpdate);

        Event::massUpdate($toUpdate);

        return 0;
    }

    private function handleChild(Event $rescheduledEvent, array &$children): void
    {
        $child = $rescheduledEvent->child;
        unset($rescheduledEvent->child);
        $repositoryClass = CalendarEventType::repositoryFromTypeClass($child::class);

        $children[$repositoryClass][] = $child;
    }

    private function handleAssignChildren(Collection $insertedClones, array $insertedChildren, array &$toUpdate): void
    {
        /** @var Event $event */
        foreach ($insertedClones as $event) {
            $event->child_id = $insertedChildren[$event->getPreservedIdentifier()];

            $toUpdate[] = $event;
        }
    }

    private function handleInsertedChildren(array $children, array &$insertedChildren): void
    {
        array_walk($children, function (array $events, string $repositoryClass) use (&$insertedChildren) {
            /** @var EventRepository $repository */
            $repository = App::make($repositoryClass);

            $inserted = $repository->insertRescheduled(new Collection($events))->map(function (Event $event) {
                return [
                    'preserved_id' => $event->getPreservedIdentifier(),
                    'id' => $event->id,
                ];
            })->pluck('id', 'preserved_id')->all();

            $insertedChildren = $insertedChildren + $inserted;
        });
    }

    private function setTimeInterval(Event $rescheduledEvent): void
    {
        if($rescheduledEvent->repeatable_status === CalendarEventRepeat::WEEKLY) {
            $rescheduledEvent->starts_at = (new Carbon($rescheduledEvent->starts_at))->addWeek()->format(self::DATE_FORMAT);
            $rescheduledEvent->ends_at = (new Carbon($rescheduledEvent->ends_at))->addWeek()->format(self::DATE_FORMAT);
        }
        if($rescheduledEvent->repeatable_status === CalendarEventRepeat::DAILY) {
            $rescheduledEvent->starts_at = (new Carbon($rescheduledEvent->starts_at))->addDay()->format(self::DATE_FORMAT);
            $rescheduledEvent->ends_at = (new Carbon($rescheduledEvent->ends_at))->addDay()->format(self::DATE_FORMAT);
        }
    }
}
