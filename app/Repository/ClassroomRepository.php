<?php

namespace App\Repository;

use App\Models\Classroom;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ClassroomRepository extends LaravelBaseRepository
{
    protected const MODEL = Classroom::class;

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
