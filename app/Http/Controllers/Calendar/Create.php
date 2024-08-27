<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Requests\CalendarRequest;
use App\Repository\CalendarRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private CalendarRepository $repository,
    ){
    }

    public function __invoke(CalendarRequest $request): JsonResponse
    {
        $calendar = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($calendar, 'calendar:create'));
    }
}
