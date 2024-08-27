<?php

namespace App\Constant\Calendar;

use App\Http\Requests\EventRequest;
use App\Http\Requests\ExamEventRequest;
use App\Http\Requests\SubjectEventRequest;
use App\Models\ExamEvent;
use App\Models\SubjectEvent;
use App\Policies\EventPolicy;
use App\Policies\ExamEventPolicy;
use App\Policies\SubjectEventPolicy;
use App\Repository\EventRepository;
use App\Repository\ExamEventRepository;
use App\Repository\SubjectEventRepository;

class CalendarEventType
{
    public final const SUBJECT = 'subject_event';
    public final const EXAM = 'exam_event';

    public static function repository(?string $type = null): string
    {
        return match ($type) {
            self::SUBJECT => SubjectEventRepository::class,
            self::EXAM => ExamEventRepository::class,
            default => EventRepository::class,
        };
    }

    public static function repositoryFromTypeClass(?string $eventClass): string
    {
        return match ($eventClass) {
            SubjectEvent::class => SubjectEventRepository::class,
            ExamEvent::class => ExamEventRepository::class,
            default => EventRepository::class,
        };
    }

    public static function serializationGroups(?string $type = null): array
    {
        return match ($type) {
            self::SUBJECT => [
                'create' => [
                    ...self::baseCreateSerializationGroups(),
                    'teacher:nestedSubjectEventCreate',
                    'classroom:nestedSubjectEventCreate',
                    'schoolClass:nestedSubjectEventCreate'
                ],
                'update' => [
                    ...self::baseUpdateSerializationGroups(),
                    'teacher:nestedSubjectEventUpdate',
                    'classroom:nestedSubjectEventUpdate',
                    'schoolClass:nestedSubjectEventUpdate'
                ],
                'retrieve' => [
                    ...self::baseRetrieveSerializationGroups(),
                    'teacher:nestedSubjectEventRetrieve',
                    'classroom:nestedSubjectEventRetrieve',
                    'schoolClass:nestedSubjectEventRetrieve'
                ],
                'collection' => self::baseCollectionSerializationGroups(),
            ],
            self::EXAM => [
                'create' => [
                    ...self::baseCreateSerializationGroups(),
                    'teacher:nestedExamEventCreate',
                    'schoolClass:nestedExamEventCreate',
                    'subject:nestedExamEventCreate',
                ],
                'update' => [
                    ...self::baseUpdateSerializationGroups(),
                    'teacher:nestedExamEventUpdate',
                    'schoolClass:nestedExamEventUpdate',
                    'subject:nestedExamEventUpdate',
                ],
                'retrieve' => [
                    ...self::baseRetrieveSerializationGroups(),
                    'teacher:nestedExamEventRetrieve',
                    'schoolClass:nestedExamEventRetrieve',
                    'subject:nestedExamEventRetrieve',
                ],
                'collection' => self::baseCollectionSerializationGroups(),
            ],
            default => [
                'create' => self::baseCreateSerializationGroups(),
                'update' => self::baseUpdateSerializationGroups(),
                'retrieve' => self::baseRetrieveSerializationGroups(),
                'collection' => self::baseCollectionSerializationGroups(),
            ],
        };
    }

    public static function request(?string $type = null): string
    {
        return match ($type) {
            self::SUBJECT => SubjectEventRequest::class,
            self::EXAM => ExamEventRequest::class,
            default => EventRequest::class,
        };
    }

    public static function policy(?string $type = null): string
    {
        return match ($type) {
            self::SUBJECT => SubjectEventPolicy::class,
            self::EXAM => ExamEventPolicy::class,
            default => EventPolicy::class,
        };
    }

    private static function baseCreateSerializationGroups(): array
    {
        return [
            'event:create',
            'calendar:nestedEventCreate',
            'user:nestedEventCreate',
        ];
    }

    private static function baseUpdateSerializationGroups(): array
    {
        return [
            'event:update',
        ];
    }

    private static function baseRetrieveSerializationGroups(): array
    {
        return [
            'event:retrieve',
            'user:nestedEventRetrieve',
        ];
    }

    private static function baseCollectionSerializationGroups(): array
    {
        return [
            'event:collection'
        ];
    }
}
