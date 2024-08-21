<?php

namespace App\Http\Controllers\Guardian;

use App\Models\Guardian;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Retrieve
{
    public function __invoke(Guardian $guardian): JsonResponse
    {
        return new JsonResponse(Serializer::normalize($guardian, ['guardian:retrieve', 'media:nestedGuardianRetrieve']));
    }
}
