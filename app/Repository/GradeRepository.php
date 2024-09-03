<?php

namespace App\Repository;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class GradeRepository extends LaravelBaseRepository
{
    protected const MODEL = Grade::class;

    public function __construct(
        private StudentRepository $studentRepository,
        private SubjectRepository $subjectRepository,
    ){
    }

    public function create(array $data): Grade
    {
        $grade = new Grade();
        $this->modifyShardData($grade, $data);

        $this->associateTeacher($grade);
        $student = $this->associateStudent($grade, $data['student_id']);
        $this->associateSchoolClass($grade, $student);
        $this->associateSubject($grade, $data['subject_id']);

        $grade->save();

        return $grade;
    }

    public function update(array $data, Grade $grade): Grade
    {
        $this->modifyShardData($grade, $data);

        $grade->save();

        return $grade;
    }

    private function modifyShardData(Grade $grade, array $data): void
    {
        $grade->grade = $data['grade'];
        $grade->comment = $data['comment'] ?? null;
        $grade->is_final = $data['is_final'] ?? false;
    }

    private function associateTeacher(Grade $grade): void
    {
        /** @var Teacher $teacher */
        $teacher = Auth::user();
        $grade->teacher()->associate($teacher);
    }

    private function associateStudent(Grade $grade, int $studentId): Student
    {
        /** @var Student $student */
        $student = $this->studentRepository->findOneByOr404(['id' => $studentId]);
        $grade->student()->associate($student);

        return $student;
    }

    private function associateSchoolClass(Grade $grade, Student $student): void
    {
        $schoolClass = $student->schoolClass()->first();
        $grade->schoolClass()->associate($schoolClass);
    }

    private function associateSubject(Grade $grade, int $subjectId): void
    {
        $subject = $this->subjectRepository->findOneByOr404(['id' => $subjectId]);
        $grade->subject()->associate($subject);
    }
}
