<?php

namespace App\Http\Controllers\Equipment;

use App\Models\Equipment;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __invoke(Equipment $equipment): JsonResponse
    {
        $equipment->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
