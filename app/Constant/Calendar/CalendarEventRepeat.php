<?php

namespace App\Constant\Calendar;

use App\Constant\Constant;

class CalendarEventRepeat extends Constant
{
    public final const PAUSED = 'paused';
    public final const CANCELED = 'canceled';
    public final const DAILY = 'daily';
    public final const WEEKLY = 'weekly';

    public static function ongoing(): array
    {
        return [self::PAUSED, self::DAILY, self::WEEKLY];
    }

    public static function ongoingNotPaused(): array
    {
        return array_diff(self::ongoing(), [self::PAUSED]);
    }
}
