<?php

namespace App\Repository;

use App\Constant\Calendar\CalendarEventRepeat;
use App\Constant\Calendar\CalendarEventStatus;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\SubjectEvent;
use App\Trait\CanAssociateSchoolClass;
use App\Trait\CanAssociateTeacher;
use Illuminate\Database\Eloquent\Builder;

class SubjectEventRepository extends EventRepository
{
    use CanAssociateTeacher, CanAssociateSchoolClass;

    protected const MODEL = SubjectEvent::class;

    public function __construct(
        private SchoolClassRepository $schoolClassRepository,
        private TeacherRepository $teacherRepository,
        private ClassroomRepository $classroomRepository,
    ){
    }

    public function create(array $data, Calendar $calendar): SubjectEvent
    {
        $event = new SubjectEvent();

        $this->associateSchoolClass($event, $data['school_class_id']);
        $this->associateTeacher($event, $data['teacher_id']);
        $this->associateClassroom($event, $data['classroom_id']);

        $this->baseCreate($event, $calendar, $data);

        return $event;
    }

    public function update(array $data, SubjectEvent|Event $event): SubjectEvent
    {
        if($event->teacher_id !== $data['teacher_id']) {
            $this->associateTeacher($event, $data['teacher_id']);
        }
        if($event->classroom_id !== $data['classroom_id']) {
            $this->associateClassroom($event, $data['classroom_id']);
        }
        if($event->school_class_id !== $data['school_class_id']) {
            $this->associateSchoolClass($event, $data['school_class_id']);
        }

        $this->modifySharedData($event, $data);

        $event->save();

        return $event;
    }

    public function findAlreadyScheduled(
        string $startsAt,
        string $endsAt,
        int $teacherId,
        int $schoolClassId,
        int $classroomId
    ): ?SubjectEvent {
        $builder = $this->getBuilder();

        return $builder->join('events', 'events.child_id', 'subject_events.id')
            ->where('events.child_type', self::MODEL)
            ->where(function (Builder $builder) {
                $builder->where(function (Builder $builder) {
                    $builder->whereNotNull('events.repeatable_status')
                        ->whereIn('events.repeatable_status', CalendarEventRepeat::ongoing());
                })->orWhereIn('events.status', CalendarEventStatus::ongoing());
            })->where(function (Builder $builder) use ($teacherId, $classroomId, $schoolClassId) {
                $builder->where('subject_events.teacher_id', $teacherId)
                    ->orWhere('subject_events.classroom_id', $classroomId)
                    ->orWhere('subject_events.school_class_id', $schoolClassId);
            })->where(function (Builder $builder) use($startsAt, $endsAt) {
                $builder->where(function ($builder) use($startsAt, $endsAt) {
                    $builder->where('events.starts_at', '<=', $startsAt)
                        ->where('events.ends_at', '>=', $startsAt);
                })->orWhere(function (Builder $builder) use($startsAt, $endsAt) {
                    $builder->where('events.starts_at', '<=', $endsAt)
                        ->where('events.ends_at', '>=', $endsAt);
                });
            })->first();
    }

    private function associateClassroom(SubjectEvent $event, int $classroomId): void
    {
        $classroom = $this->classroomRepository->findOneByOr404(['id' => $classroomId]);
        $event->classroom()->associate($classroom);
    }
}
