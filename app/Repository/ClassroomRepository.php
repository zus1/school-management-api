<?php

namespace App\Repository;

use App\Models\Classroom;
use App\Models\Equipment;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ClassroomRepository extends LaravelBaseRepository
{
    protected const MODEL = Classroom::class;

    public function __construct(
        private EquipmentRepository $equipmentRepository,
    ){
    }

    public function create(array $data): Classroom
    {
        $classroom = new Classroom();

        $this->modifySharedData($classroom, $data);

        $classroom->save();

        return $classroom;
    }

    public function update(array $data, Classroom $classroom): Classroom
    {
        $this->modifySharedData($classroom, $data);

        $classroom->save();

        return $classroom;
    }

    public function toggleEquipment(Classroom $classroom, Equipment $equipment, string $action, ?int $quantity): Classroom
    {
        if($action === 'add') {
            $classroom->equipments()->attach($equipment->id, ['quantity' => $quantity]);
            $this->equipmentRepository->reduceAvailableQuantity($equipment, $quantity);
        }
        if($action === 'remove') {
            $this->detachEquipment($classroom, $equipment);
        }

        return $classroom;
    }

    public function updateEquipmentQuantity(
        Classroom $classroom,
        Equipment $equipment,
        string $action,
        int $quantity
    ): Classroom {
        $currentQuantity = $this->getCurrentEquipmentQuantity($classroom, $equipment);

        if($action === 'increase') {
            $this->equipmentRepository->reduceAvailableQuantity($equipment, $quantity);

            $classroom->equipments()->updateExistingPivot($equipment->id, ['quantity' => $currentQuantity + $quantity]);
        }
        if($action === 'decrease') {
            if(($newQuantity = $currentQuantity - $quantity) < 0) {
                throw new HttpException(400, 'Trying to reduce more then there is');
            }

            $classroom->equipments()->updateExistingPivot($equipment->id, ['quantity' => $newQuantity]);
            $this->equipmentRepository->increaseAvailableQuantity($equipment, $quantity);
        }

        return $classroom;
    }

    private function getCurrentEquipmentQuantity(Classroom $classroom, Equipment $equipment): int
    {
        $currentQuantity = $classroom->equipments()->where('equipments.id', $equipment->id)->withPivot('quantity')->first();
        $currentQuantityArr = $currentQuantity->toArray();

        return $currentQuantityArr['pivot']['quantity'] ?? 0;
    }

    private function detachEquipment(Classroom $classroom, Equipment $equipment): void
    {
        $assignedQuantity = $classroom->equipments()
            ->where('equipments.id', $equipment->id)
            ->withPivot('quantity')
            ->first();
        $assignedQuantityArr = $assignedQuantity->toArray();
        $this->equipmentRepository->increaseAvailableQuantity($equipment, $assignedQuantityArr['pivot']['quantity']);

        $classroom->equipments()->detach($equipment->id);
    }

    private function modifySharedData(Classroom $classroom, array $data): void
    {
        $size = $data['size'];

        $classroom->name = $data['name'];
        $classroom->description = $data['description'];
        $classroom->size = sprintf('%sx%sx%s', $size['length'], $size['width'], $size['height']);
        $classroom->max_capacity = $data['max_capacity'];
        $classroom->floor = $data['floor'];
        $classroom->number = $data['number'];
        $classroom->number_of_seats = $data['number_of_seats'];
        $classroom->purpose = $data['purpose'];
    }
}
