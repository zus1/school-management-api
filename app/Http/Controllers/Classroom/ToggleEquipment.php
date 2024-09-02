<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Classroom;
use App\Models\Equipment;
use App\Repository\ClassroomRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Zus1\Serializer\Facade\Serializer;

class ToggleEquipment
{
    public function __construct(
        private ClassroomRepository $repository,
    ){
    }

    public function __invoke(Request $request, Classroom $classroom, Equipment $equipment): JsonResponse
    {
        $action = $request->query('action');
        $quantity = $request->input('quantity');

        $classroom = $this->repository->toggleEquipment($classroom, $equipment, $action, $quantity);

        return new JsonResponse(Serializer::normalize($classroom,
            ['classroom:toggleEquipment', 'equipment:nestedClassroomToggleEquipment']));
    }
}
