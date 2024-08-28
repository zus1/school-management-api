<?php

namespace App\Repository;

use App\Models\TeacherSubject;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class TeacherSubjectRepository extends LaravelBaseRepository
{
    protected const MODEL = TeacherSubject::class;
}
