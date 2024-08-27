<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('calendar-event:send-notifications')->everyMinute()->withoutOverlapping();
Schedule::command('calendar-event:reschedule-repeatable')->daily()->at('23:59')->withoutOverlapping();
