<?php

namespace App\Repository;

use App\Constant\Calendar\CalendarEventRepeat;
use App\Constant\Calendar\CalendarEventStatus;
use App\Constant\UserType;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class EventRepository extends LaravelBaseRepository
{
    protected const MODEL = Event::class;

    public function create(array $data, Calendar $calendar): Event
    {
        $event = new Event();

        $this->baseCreate($event, $calendar, $data);

        return $event;
    }

    public function handleNotifications(int $chunkSize, array $callback): void
    {
        $builder = $this->getBuilder();

        $builder->where('notifications_sent', false)
            ->where('starts_at', '<=', Carbon::now()->addMinutes(config('calendar-event.notification.before_start')))
            ->with('notifyUsers')
            ->chunk($chunkSize, $callback);
    }

    public function findForReschedule(): Collection
    {
        $builder = $this->getBuilder();

        return $builder->whereIn('repeatable_status', CalendarEventRepeat::ongoingNotPaused())
            ->whereBetween('starts_at', [
                Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),
                Carbon::now()->endOfDay()->format('Y-m-d H:i:s')
            ])->get();
    }

    public function insertAndReturnIds(Collection $events): \Illuminate\Support\Collection
    {
        $totalEvents = $events->count();

        $this->getBuilder()->insert($events->toArray());

        return $this->getBuilder()->orderBy('id', 'DESC')->limit($totalEvents)->pluck('id');
    }

    public function insertRescheduled(Collection $events): Collection
    {
        $totalEvents = $events->count();
        $eventsArr = $events->toArray();

        $this->getBuilder()->insert($eventsArr);
        $inserted = $this->getBuilder()->orderBy('id', 'DESC')->limit($totalEvents)->get()->reverse();

        for ($i = 0; $i < $totalEvents; $i++) {
            /** @var Event $new */
            $new = $inserted[$i];
            /** @var Event $old */
            $old = $events[$i];

            $new->setPreservedIdentifier($old->getPreservedIdentifier());

            //dd($new->getCurrentChildId(), $old->getCurrentChildId());
        }

        return new Collection($inserted);
    }

    protected function baseCreate(Event $event, Calendar $calendar, array $data): void
    {
        $this->modifySharedData($event, $data);

        $event->calendar()->associate($calendar);
        $this->associateCreator($event);

        $event->save();

        $this->attachNotifyUsers($event, $data['notify_user_ids']);
    }

    private function attachNotifyUsers(Event $event, array $userIds): void
    {
        $parentIds = [];

        foreach ($userIds as $childTypeId) {
            [$usertype, $childId] = explode('.', $childTypeId);
            /** @var UserRepository $repository */
            $repository = App::make(UserType::repositoryClass($usertype));
            /** @var User $user */
            $user = $repository->findOneByOr404(['id' => $childId]);

            try {
                $parent = $user->parent()->first();
            } catch (QueryException) {
                $parentIds[] = $user->id;

                continue;
            }

            $parentIds[] = $parent->id;
        }

        $event->notifyUsers()->attach($parentIds);
    }

    public function update(array $data, Event $event): Event
    {
        $this->modifySharedData($event, $data);

        $event->save();

        return $event;
    }

    public function updateStatus(Event $event, string $status): Event
    {
        $event->status = $status;

        $event->save();

        return $event;
    }

    public function updateRepeatableStatus(Event $event, string $status): Event
    {
        $event->repeatable_status = $status;

        $event->save();

        return $event;
    }

    protected function modifySharedData(Event $event, array $data): void
    {
        $statsAt = new Carbon($data['starts_at']);
        $endsAt = new Carbon($data['ends_at']);

        $event->title = $data['title'];
        $event->content = $data['content'];
        $event->starts_at = $statsAt->format('Y-m-d H:i:s');
        $event->ends_at = $endsAt->format('Y-m-d H:i:s');
        $event->duration = $statsAt->diffInMinutes($endsAt);
        $event->status = CalendarEventStatus::SCHEDULED;
        $event->repeatable_status = $data['repeatable_status'] ?? null;
    }

    private function associateCreator(Event $event): void
    {
        /** @var User $auth */
        $auth = Auth::user();

        $event->creator()->associate($auth);
    }
}
