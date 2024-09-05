<?php

namespace App\Repository;

use App\Interface\SchoolDirectoryInterface;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

abstract class SchoolDirectoryBaseRepository extends LaravelBaseRepository
{
    private StudentRepository $studentRepository;
    private SubjectRepository $subjectRepository;

    public function setStudentRepository(StudentRepository $repository): void
    {
        $this->studentRepository = $repository;
    }

    public function setSubjectRepository(SubjectRepository $repository): void
    {
        $this->subjectRepository = $repository;
    }

    protected function setBaseProperties(SchoolDirectoryInterface $model, array $data): void
    {
        $this->associateTeacher($model);
        $student = $this->associateStudent($model, $data['student_id']);
        $this->associateSchoolClass($model, $student);
        $this->associateSubject($model, $data['subject_id']);
    }

    private function associateTeacher(SchoolDirectoryInterface $model): void
    {
        /** @var Teacher $teacher */
        $teacher = Auth::user();
        $model->teacher()->associate($teacher);
    }

    private function associateStudent(SchoolDirectoryInterface $model, int $studentId): Student
    {
        /** @var Student $student */
        $student = $this->studentRepository->findOneByOr404(['id' => $studentId]);
        $model->student()->associate($student);

        return $student;
    }

    private function associateSchoolClass(SchoolDirectoryInterface $model, Student $student): void
    {
        $schoolClass = $student->schoolClass()->first();
        $model->schoolClass()->associate($schoolClass);
    }

    private function associateSubject(SchoolDirectoryInterface $model, int $subjectId): void
    {
        $subject = $this->subjectRepository->findOneByOr404(['id' => $subjectId]);
        $model->subject()->associate($subject);
    }
}
