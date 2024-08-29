<?php

namespace App\Constant;

class RouteName
{
    public const AUTH_REGISTER = 'register';
    public const AUTH_VERIFY_PHONE = 'auth_verify_phone';

    public const STUDENT_CREATE = 'student_create';
    public const STUDENT_ONBOARDING = 'student_onboarding';
    public const STUDENT_UPDATE = 'student_update';
    public const STUDENT_DELETE = 'student_delete';
    public const STUDENT = 'student';
    public const STUDENTS = 'students';

    public const TEACHER = 'teacher';
    public const TEACHERS = 'teachers';
    public const TEACHER_UPDATE = 'teacher_update';
    public const TEACHER_DELETE = 'teacher_delete';

    public const GUARDIAN = 'guardian';
    public const GUARDIANS = 'guardians';
    public const GUARDIAN_UPDATE = 'guardian_update';
    public const GUARDIAN_DELETE = 'guardian_delete';

    public const USER_TOGGLE_ACTIVE = 'user_toggle_active';

    public const ME = 'me';
    public const ME_UPDATE = 'me_update';
    public const ME_AVATAR = 'me_avatar';
    public const ME_DELETE = 'me_delete';

    public const SCHOOL_CLASS_CREATE = 'school_class_create';
    public const SCHOOL_CLASS_UPDATE = 'school_class_update';
    public const SCHOOL_CLASS_DELETE = 'school_class_delete';
    public const SCHOOL_CLASSES = 'school_classes';

    public const CALENDAR_CREATE = 'calendar_create';
    public const CALENDAR_UPDATE = 'calendar_update';
    public const CALENDAR_DELETE = 'calendar_delete';
    public const CALENDAR_TOGGLE_ACTIVE = 'calendar_toggle_active';
    public const CALENDARS = 'calendars';

    public const EVENT_CREATE = 'event_create';
    public const EVENT_UPDATE = 'event_update';
    public const EVENT_DELETE = 'event_delete';
    public const EVENT  = 'event';
    public const EVENTS = 'events';
    public const EVENT_UPDATE_STATUS = 'update_event_status';
    public const EVENT_UPDATE_REPEATABLE_STATUS = 'update_event_repeatable_status';
    public const EVENT_TOGGLE_NOTIFY = 'event_toggle_notify';

    public const SUBJECT_CREATE = 'subject_create';
    public const SUBJECT_UPDATE = 'subject_update';
    public const SUBJECT_DELETE = 'subject_delete';
    public const SUBJECT_TOGGLE_LECTURER = 'subject_toggle_lecturer';
    public const SUBJECT_TOGGLE_LECTURER_CLASSES = 'subject_toggle_lecturer_classes';

    public const TEACHER_SUBJECT = 'teacher_subject';
    public const TEACHER_SUBJECTS = 'teacher_subjects';

    public const CLASSROOM_CREATE = 'classroom_create';
    public const CLASSROOM_UPDATE = 'classroom_update';
    public const CLASSROOM_DELETE = 'classroom_delete';
    public const CLASSROOM = 'classroom';
    public const CLASSROOMS = 'classrooms';
}
