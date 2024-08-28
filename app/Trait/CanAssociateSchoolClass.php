<?php

namespace App\Trait;

use App\Interface\HasSchoolClassInterface;
use App\Repository\SchoolClassRepository;
use Illuminate\Support\Facades\App;

trait CanAssociateSchoolClass
{
    private function associateSchoolClass(HasSchoolClassInterface $event, int $schoolClassId): void
    {
        $repository = App::make(SchoolClassRepository::class);
        $schoolClass = $repository->findOneByOr404(['id' => $schoolClassId]);
        $event->schoolClass()->associate($schoolClass);
    }
}
