<?php

namespace App\Trait;

use App\Interface\HasTeacherInterface;
use App\Repository\TeacherRepository;
use Illuminate\Support\Facades\App;

trait CanAssociateTeacher
{
    private function associateTeacher(HasTeacherInterface $event, int $teacherId): void
    {
        $repository = App::make(TeacherRepository::class);
        $teacher = $repository->findOneByOr404(['id' => $teacherId]);
        $event->teacher()->associate($teacher);
    }
}
