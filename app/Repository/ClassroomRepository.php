<?php

namespace App\Repository;

use App\Models\Classroom;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ClassroomRepository extends LaravelBaseRepository
{
    protected const MODEL = Classroom::class;
}
