<?php

namespace App\Http\Controllers\Calendar;

use App\Models\Calendar;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Calendar $calendar): JsonResponse
    {
        $calendar->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
