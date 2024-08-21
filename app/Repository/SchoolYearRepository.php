<?php

namespace App\Repository;

use App\Models\SchoolYear;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class SchoolYearRepository extends LaravelBaseRepository
{
    protected const MODEL = SchoolYear::class;
}
