<?php

namespace App\Constant\Calendar;

use App\Constant\Constant;

class CalendarEventStatus extends Constant
{
    public final const SCHEDULED = 'scheduled';
    public final const IN_PROGRESS = 'in_progress';
    public final const FINISHED = 'finished';

    public static function ongoing(): array
    {
        return [self::SCHEDULED, self::IN_PROGRESS];
    }
}
