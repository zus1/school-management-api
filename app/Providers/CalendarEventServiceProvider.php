<?php

namespace App\Providers;

use App\Constant\Calendar\CalendarEventType;
use App\Http\Controllers\Event\Create;
use App\Http\Controllers\Event\Update;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CalendarEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if($this->app->runningInConsole()) {
            return;
        }

        $eventType = $this->getEventType();

        $this->assignRequestInstance(Create::class, $eventType);
        $this->assignRequestInstance(Update::class, $eventType);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            return;
        }

        $eventType = $this->getEventType();

        $policyClass = CalendarEventType::policy($eventType);

        Gate::policy(Event::class, $policyClass);
    }

    private function assignRequestInstance(string $controllerClass, ?string $eventType): void
    {
        $this->app->when($controllerClass)
            ->needs(EventRequest::class)
            ->give(fn() => $this->app->make(CalendarEventType::request($eventType)));
    }

    private function getEventType(): ?string
    {
        /** @var Request $request */
        $request = $this->app->get(Request::class);

        return $request->query('event_type');
    }
}
