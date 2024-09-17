<?php

namespace App\Constant\Analytics;

use App\Constant\Constant;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DatePeriod extends Constant
{
    public final const TODAY = 'today';
    public final const YESTERDAY = 'yesterday';
    public final const THIS_MONTH = 'this_month';
    public final const LAST_MONTH = 'last_month';
    public final const THIS_YEAR = 'this_year';
    public final const LAST_YEAR = 'last_year';

    public static function unit(string $period): string
    {
        return match ($period) {
            self::TODAY,
            self::YESTERDAY => DateUnit::HOUR,
            self::THIS_MONTH,
            self::LAST_MONTH => DateUnit::DAY,
            self::THIS_YEAR,
            self::LAST_YEAR => DateUnit::MONTH,
            default => throw  new HttpException(500, 'Unknown period '.$period),
        };
    }

    public static function boundaries(string $period): array
    {
        return match ($period) {
            self::TODAY => [
                'from' => Carbon::now()->startOfDay(),
                'to' => Carbon::now()->endOfDay(),
            ],
            self::YESTERDAY => [
                'from' => Carbon::now()->subDay()->startOfDay(),
                'to' => Carbon::now()->subDay()->endOfDay(),
            ],
            self::THIS_MONTH => [
                'from' => Carbon::now()->startOfMonth(),
                'to' => Carbon::now()->endOfMonth(),
            ],
            self::LAST_MONTH => [
                'from' => Carbon::now()->subMonth()->startOfMonth(),
                'end' => Carbon::now()->subMonth()->endOfMonth(),
            ],
            self::THIS_YEAR => [
                'from' => Carbon::now()->startOfYear(),
                'to' => Carbon::now()->endOfYear(),
            ],
            self::LAST_YEAR => [
                'from' => Carbon::now()->subYear()->startOfYear(),
                'to' => Carbon::now()->subYear()->startOfYear(),
            ],
            default => throw  new HttpException(500, 'Unknown period '.$period),
        };
    }
}
