<?php

namespace App\Repository;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\ExamEvent;
use App\Trait\CanAssociateSchoolClass;
use App\Trait\CanAssociateTeacher;

class ExamEventRepository extends EventRepository
{
    use CanAssociateTeacher, CanAssociateSchoolClass;

    protected const MODEL = ExamEvent::class;

    public function __construct(
        private SchoolClassRepository $schoolClassRepository,
        private SubjectRepository $subjectRepository,
    ){
    }

    public function create(array $data, Calendar $calendar): ExamEvent
    {
        $examEvent = new ExamEvent();

        $this->associateTeacher($examEvent, $data['teacher_id']);
        $this->associateSchoolClass($examEvent, $data['school_class_id']);
        $this->associateSubject($examEvent, $data['subject_id']);

        $this->baseCreate($examEvent, $calendar, $data);

        return $examEvent;

    }

    public function update(array $data, ExamEvent|Event $event): Event
    {
        $this->modifySharedData($event, $data);

        if($event->school_class_id !== $data['school_class_id']) {
            $this->associateSchoolClass($event, $data['school_class_id']);
        }
        if($event->teacher_id !== $data['teacher_id']) {
            $this->associateTeacher($event, $data['teacher_id']);
        }
        if($event->subject_id !== $data['subject_id']) {
            $this->associateSubject($event, $data['subject_id']);
        }

        $event->save();

        return $event;
    }

    private function associateSubject(ExamEvent $event, int $subjectId): void
    {
        $subject = $this->subjectRepository->findOneByOr404(['id' => $subjectId]);
        $event->subject()->associate($subject);
    }
}
