<?php

namespace App\Constant\Analytics;

use App\Constant\Constant;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DateUnit extends Constant
{
    public final const HOUR = 'hour';
    public final const DAY = 'day';
    public final const MONTH = 'month';
    public final const YEAR = 'year';

    public static function getFormat(string $unit): array
    {
        return match ($unit) {
            self::HOUR => ['format' => 'Y-m-d H', 'db' => '%Y-%m-%d %H'],
            self::DAY => ['format' => 'Y-m-d', 'db' => '%Y-%m-%d'],
            self::MONTH => ['format' => 'Y-m', 'db' => '%Y-%m'],
            self::YEAR => ['format' => 'Y', 'db' => '%Y'],
            default => throw new HttpException(500, 'Unknown period '.$unit),
        };
    }

    public static function getFullPeriod(Carbon $forDate, string $unit): array
    {
        $format = self::getFormat($unit)['format'];

        return match ($unit) {
            self::HOUR => self::allMinutes($forDate, $format),
            self::DAY => self::allHours($forDate, $format),
            self::MONTH => self::allDays($forDate, $format),
            self::YEAR => self::allMonths($forDate, $format),
            default => throw new HttpException(500, 'Unknown period '.$unit),
        };
    }

    private static function allMinutes(Carbon $forDate, string $format): array
    {
        $date = $forDate->startOfHour();

        $allMinutes = [
            $date->format($format),
        ];

        for($i = 1, $max = 59; $i <= $max; $i++) {
            $allMinutes[] = $date->addMinute()->format($format);
        }

        return $allMinutes;
    }

    private static function allHours(Carbon $forDate, string $format): array
    {
        $date = $forDate->startOfDay();

        $allHours = [
            $date->format($format),
        ];

        for($i = 1, $max = 24; $i <= $max; $i++) {
            $allHours[] = $date->addHour()->format($format);
        }

        return $allHours;
    }

    private static function allDays(Carbon $forDate, string $format): array
    {
        $start = $forDate->startOfMonth();
        $end = $forDate->endOfMonth()->day;

        $allDays = [
            $start->format($format)
        ];

        for ($i = $start->day; $i <= $end; $i++) {
            $allDays[] = $start->addDay()->format($format);
        }

        return $allDays;
    }

    private static function allMonths(Carbon $forDate, string $format): array
    {
        $date = $forDate->startOfYear();

        $allMonths = [
            $date->format($format)
        ];

        for($i = 1, $max = 12; $i <= $max; $i++) {
            $allMonths[] = $date->addMonth()->format($format);
        }

        return $allMonths;
    }
}
