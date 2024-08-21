<?php

namespace App\Repository;

use App\Models\SchoolClass;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class SchoolClassRepository extends LaravelBaseRepository
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private SchoolYearRepository $schoolYearRepository,
    ){
    }

    protected const MODEL = SchoolClass::class;

    public function create(array $data): SchoolClass
    {
        $schoolClass = new SchoolClass();

        return $this->createOrUpdate($schoolClass, $data);
    }

    public function update(array $data, SchoolClass $schoolClass): SchoolClass
    {
        return $this->createOrUpdate($schoolClass, $data);
    }

    private function createOrUpdate(SchoolClass $schoolClass, array $data): SchoolClass
    {
        $schoolClass->name = $data['name'];

        $this->associateTeacher($schoolClass, $data['teacher_id']);
        $this->associateSchoolYear($schoolClass, $data['school_year_id']);

        $schoolClass->save();

        return $schoolClass;
    }

    private function associateTeacher(SchoolClass $schoolClass, int $teacherId): void
    {
        $teacher = $this->teacherRepository->findOneByOr404(['id' => $teacherId]);

        $schoolClass->teacher()->associate($teacher);
    }

    private function associateSchoolYear(SchoolClass $schoolClass, int $schoolYearId): void
    {
        $schoolYear = $this->schoolYearRepository->findOneByOr404(['id' => $schoolYearId]);

        $schoolClass->schoolYear()->associate($schoolYear);
    }
}
