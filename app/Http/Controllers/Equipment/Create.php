<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Requests\EquipmentRequest;
use App\Repository\EquipmentRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Create
{
    public function __construct(
        private EquipmentRepository $repository,
    ){
    }

    public function __invoke(EquipmentRequest $request): JsonResponse
    {
        $equipment = $this->repository->create($request->input());

        return new JsonResponse(Serializer::normalize($equipment, 'equipment:create'));
    }
}
