<?php

namespace App\Repository;

use App\Models\Subject;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class SubjectRepository extends LaravelBaseRepository
{
    public const MODEL = Subject::class;
}
