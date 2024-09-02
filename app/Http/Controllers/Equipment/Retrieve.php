<?php

namespace App\Http\Controllers\Equipment;

use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Equipment $equipment): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($equipment, 'equipment:retrieve'));
    }
}
