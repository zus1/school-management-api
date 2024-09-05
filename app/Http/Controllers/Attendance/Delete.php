<?php

namespace App\Http\Controllers\Attendance;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
