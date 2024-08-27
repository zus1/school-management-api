<?php

namespace App\Http\Controllers\Calendar;

use App\Models\Calendar;
use App\Repository\CalendarRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class ToggleActive
{
    public function __construct(
        private CalendarRepository $repository
    ){
    }

    public function __invoke(Calendar $calendar, string $active): JsonResponse
    {
        $active = $active === 'true';

        /** @var Calendar $calendar */
        $calendar = $this->repository->toggleActive($calendar, $active);

        return new JsonResponse(Serializer::normalize($calendar, 'calendar:toggleActive'));
    }
}
