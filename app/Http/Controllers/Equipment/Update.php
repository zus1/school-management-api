<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Repository\EquipmentRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private EquipmentRepository $repository,
    ){
    }

    public function __invoke(EquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $equipment = $this->repository->update($request->input(), $equipment);

        return new JsonResponse(Serializer::normalize($equipment,  'equipment:update'));
    }
}
