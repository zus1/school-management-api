<?php

namespace App\Repository;

use App\Models\Equipment;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class EquipmentRepository extends LaravelBaseRepository
{
    protected const MODEL = Equipment::class;

    public function create(array $data): Equipment
    {
        $equipment = new Equipment();
        $this->modifySharedFields($equipment, $data);
        $equipment->total_quantity = $data['total_quantity'];
        $equipment->available_quantity = $data['total_quantity'];

        $equipment->save();

        return $equipment;
    }

    public function update(array $data, Equipment $equipment): Equipment
    {
        if($equipment->total_quantity !== $data['total_quantity']) {
            $data['total_quantity'] > $equipment->total_quantity ?
                $equipment->available_quantity += ($data['total_quantity'] - $equipment->total_quantity) :
                $equipment->available_quantity -= ($equipment->total_quantity - $data['total_quantity']);
            $equipment->total_quantity = $data['total_quantity'];
        }

        $this->modifySharedFields($equipment, $data);

        $equipment->save();

        return $equipment;
    }

    public function reduceAvailableQuantity(Equipment $equipment, int $quantityToReduce): Equipment
    {
        if($equipment->available_quantity - $quantityToReduce < 0) {
            throw new HttpException(
                400,
                'Unable to reduce available quantity, remaining amount will be less then 0'
            );
        }

        $equipment->available_quantity -= $quantityToReduce;

        $equipment->save();

        return $equipment;
    }

    public function increaseAvailableQuantity(Equipment $equipment, int $quantityToAdd): Equipment
    {
        $equipment->available_quantity += $quantityToAdd;

        $equipment->save();

        return $equipment;
    }

    private function modifySharedFields(Equipment $equipment, array $data): void
    {
        $equipment->name = $data['name'];
        $equipment->description = $data['description'];
        $equipment->weight = $data['weight'];
        $equipment->width = $data['width'];
        $equipment->length = $data['length'];
        $equipment->height = $data['height'];
        $equipment->type = $data['type'];
        $equipment->cost_per_unit = $data['cost_per_unit'];
        $equipment->cost = $data['total_quantity'] * $data['cost_per_unit'];
    }
}
