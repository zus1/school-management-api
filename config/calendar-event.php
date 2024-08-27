<?php

return [
    'notification' => [
        'before_start' => env('CALENDAR_EVENT_NOTIFY_BEFORE_START', 10), //minutes
        'subject' => env('CALENDAR_EVENT_NOTIFICATION_SUBJECT', 'Upcoming event'),
    ],
];
