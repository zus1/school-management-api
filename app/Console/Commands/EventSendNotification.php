<?php

namespace App\Console\Commands;

use App\Mail\CalendarEventNotification;
use App\Models\Event;
use App\Models\User;
use App\Repository\EventRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EventSendNotification extends Command
{
    public const CHUNK_SIZE = 2;

    public function __construct(
        private EventRepository $repository,
    ){
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar-event:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies users for upcoming event';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->repository->handleNotifications(self::CHUNK_SIZE, [$this, 'sendNotifications']);

        return 0;
    }

    public function sendNotifications(Collection $events): void
    {
        $updatedEvents = [];

        /** @var Event $event */
        foreach ($events as $event)  {
            /** @var User $user */
            foreach ($event->notifyUsers as $user) {
                $sent = Mail::to($user->email)->send(new CalendarEventNotification($event));

                Log::channel('calendar_event')->info(sprintf(
                    'Notification for user %s %s and event %d was %s',
                    $user->first_name,
                    $user->last_name,
                    $event->title,
                    $sent !== null ? 'sent' : 'not sent'
                ));
            }

            $event->notifications_sent = true;

            $updatedEvents[] = $event;
        }

        Event::massUpdate($updatedEvents);
    }
}
