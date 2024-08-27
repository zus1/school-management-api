<?php

namespace App\Http\Middleware;

use App\Constant\Calendar\CalendarEventType;
use App\Models\Event;
use App\Repository\EventRepository;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EventInjectParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $eventId = $request->route()->parameter('event');
        $eventType = $request->query('event_type');

        /** @var EventRepository $repository */
        $repository = App::make(CalendarEventType::repository($eventType));
        /** @var Event $event */
        $event = $repository->findOneByOr404(['id' => $eventId]);

        try {
            $parent = $event->parent()->first();
        } catch (QueryException) {
            $request->route()->setParameter('event', $event);

            return $next($request);
        }

        $request->route()->setParameter('event', $parent);

        return $next($request);
    }
}
