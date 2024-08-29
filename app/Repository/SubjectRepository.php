<?php

namespace App\Repository;

use App\Dto\Repository\ToggleLecturerClassesResponseDto;
use App\Models\Subject;
use App\Models\Teacher;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class SubjectRepository extends LaravelBaseRepository
{
    public const MODEL = Subject::class;

    public function __construct(
        private SchoolYearRepository $schoolYearRepository,
    ){
    }

    public function create(array $data): Subject
    {
        $subject = new Subject();

        $this->modifySharedData($subject, $data);
        $this->associateSchoolYear($subject, $data['school_year_id']);

        $subject->save();

        if(isset($data['lecturer_ids'])) {
            $lecturerIds = array_keys($data['lecturer_ids']);

            foreach ($lecturerIds as $lecturerId) {
                $this->addLecturer($subject, (int) $lecturerId, $data['lecturer_ids'][$lecturerId]);
            }
        }

        return $subject;
    }

    public function update(array $data, Subject $subject): Subject
    {
        $this->modifySharedData($subject, $data);

        if($subject->school_year_id !== $data['school_year_id']) {
            $this->associateSchoolYear($subject, $data['school_year_id']);
        }

        $subject->save();

        return $subject;
    }

    public function toggleLecturer(Subject $subject, Teacher $lecturer, string $action, array $schoolClassIds): Subject
    {
        if($action === 'add') {
            $this->addLecturer($subject, $lecturer, $schoolClassIds);
        }
        if($action === 'remove') {
            $subject->lecturers()->detach($lecturer->id);
        }

        return $subject;
    }

    public function toggleLecturerClasses(
        Subject $subject,
        Teacher $lecturer,
        string $action,
        array $schoolClassIds
    ): Subject {
        if(!$this->checkIfLecturerHasLectures($subject, $lecturer)) {
            throw new HttpException(400, 'Please add lecturer for subject before adding his/her classes');
        }

        if($action === 'add') {
            $this->addLecturer($subject, $lecturer, $schoolClassIds);
            $subject->lecturers()
                ->wherePivotNull('school_class_id')
                ->detach($lecturer->id);
        }
        if($action === 'remove') {
            $subject->lecturers()->wherePivotIn('school_class_id', $schoolClassIds)->detach($lecturer->id);
            if(!$this->checkIfLecturerHasLectures($subject, $lecturer)) {
                $this->addLecturer($subject, $lecturer, schoolClassIds: []);
            }
        }

        return $subject;
    }

    private function checkIfLecturerHasLectures(Subject $subject, Teacher $lecturer): bool
    {
        return $subject->lecturers()->where('teachers.id', $lecturer->id)->exists();
    }

    private function addLecturer(Subject $subject, Teacher|int $lecturerId, array $schoolClassIds): void
    {
        if($lecturerId instanceof Teacher) {
            $lecturerId = $lecturerId->id;
        }

        if($schoolClassIds === []) {
            $subject->lecturers()->attach($lecturerId);

            return;
        }

        foreach ($schoolClassIds as $schoolClassId) {
            $subject->lecturers()->attach($lecturerId, ['school_class_id' => $schoolClassId]);
        }
    }

    private function modifySharedData(Subject $subject, array $data): void
    {
        $subject->name = $data['name'];
        $subject->description = $data['description'];
        $subject->is_elective = $data['is_elective'] ?? false;
    }

    private function associateSchoolYear(Subject $subject, int $schoolYearId): void
    {
        $schoolYear = $this->schoolYearRepository->findOneByOr404(['id' => $schoolYearId]);
        $subject->schoolYear()->associate($schoolYear);
    }
}
