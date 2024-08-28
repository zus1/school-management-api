<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Requests\CalendarRequest;
use App\Models\Calendar;
use App\Repository\CalendarRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private CalendarRepository $repository,
    ){
    }

    public function __invoke(CalendarRequest $request, Calendar $calendar): JsonResponse
    {
        $calendar = $this->repository->update($request->input(), $calendar);

        return new JsonResponse(Serializer::normalize($calendar, 'calendar:update'));
    }
}
