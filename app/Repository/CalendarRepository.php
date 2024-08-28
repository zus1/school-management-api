<?php

namespace App\Repository;

use App\Models\Calendar;
use App\Trait\CanActivateModel;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class CalendarRepository extends LaravelBaseRepository
{
    use CanActivateModel;

    protected const MODEL = Calendar::class;

    public function create(array $data): Calendar
    {
        $calendar = new Calendar();

        return $this->createOrUpdate($calendar, $data);
    }

    public function update(array $data, Calendar $calendar): Calendar
    {
        return $this->createOrUpdate($calendar, $data);
    }

    private function createOrUpdate(Calendar $calendar, array $data): Calendar
    {
        $calendar->name = $data['name'];
        $calendar->description = $data['description'];
        $calendar->active = true;

        $calendar->save();

        return $calendar;
    }
}
