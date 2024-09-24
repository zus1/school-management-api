<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('calendar-event:send-notifications')->everyMinute()->withoutOverlapping();
Schedule::command('calendar-event:reschedule-repeatable')->daily()->at('23:59')->withoutOverlapping();
Schedule::command('app:finish-expired-exam-sessions')->everyMinute()->withoutOverlapping();
Schedule::command('app:tuition-reminder')->mondays()->at('09:00')->withoutOverlapping();
Schedule::command('app:create-unpaid-tuitions')->yearlyOn(/** january 1st is default */)->withoutOverlapping();
