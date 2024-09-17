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
    public const CLASSROOM_TOGGLE_EQUIPMENT = 'classroom_toggle_equipment';
    public const CLASSROOM_UPDATE_EQUIPMENT_QUANTITY = 'classroom_update_equipment_quantity';

    public const EQUIPMENT_CREATE = 'equipment_create';
    public const EQUIPMENT_UPDATE = 'equipment_update';
    public const EQUIPMENT_DELETE = 'equipment_delete';
    public const EQUIPMENT = 'equipment';
    public const EQUIPMENTS = 'equipments';

    public const MESSAGE_CREATE = 'message_create';
    public const MESSAGE_UPDATE = 'message_update';
    public const MESSAGE_DELETE = 'message_delete';
    public const MESSAGES = 'messages';
    public const MESSAGE = 'message';
    public const MESSAGES_MARK_AS_READ = 'messages_mark_as_read';

    public const GRADE_CREATE = 'grade_create';
    public const GRADE_UPDATE = 'grade_update';
    public const GRADE_DELETE = 'grade_delete';
    public const GRADES = 'grades';
    public const GRADES_TOP_AVERAGE = 'grades_top_average';

    public const ATTENDANCE_CREATE = 'attendance_create';
    public const ATTENDANCE_UPDATE = 'attendance_update';
    public const ATTENDANCE_DELETE = 'attendance_delete';
    public const ATTENDANCES = 'attendances';
    public const ATTENDANCES_AGGREGATE = 'attendances_aggregate';

    public const GRADING_RULE_CREATE = 'grading_rule_create';
    public const GRADING_RULE_UPDATE = 'grading_rule_update';
    public const GRADING_RULE_DELETE = 'grading_rule_delete';
    public const GRADING_RULES = 'grading_rules';
    public const GRADING_RULE = 'grading_rule';

    public const GRADE_RANGE_CREATE = 'grade_range_create';
    public const GRADE_RANGE_UPDATE = 'grade_rang_update';
    public const GRADE_RANGE_DELETE = 'grade_range_delete';

    public const EXAM_CREATE = 'exam_create';
    public const EXAM_UPDATE = 'exam_update';
    public const EXAM_DELETE = 'exam-delete';
    public const EXAM = 'exam';
    public const EXAMS = 'exams';
    public const EXAM_TOGGLE_GRADING_RULE = 'exam_toggle_grading_rule';
    public const EXAM_TOGGLE_ALLOWED_SCHOOL_CLASS = 'exam_toggle_allowed_school_class';

    public const QUESTIONS_CREATE = 'questions_create';
    public const QUESTION_UPDATE = 'question_update';
    public const QUESTION_DELETE = 'question_delete';
    public const QUESTION = 'question';
    public const QUESTIONS = 'questions';
    public const QUESTION_CHANGE_EXAM = 'question_change_exam';

    public const ANSWER_UPDATE = 'answer_update';
    public const ANSWER_DELETE = 'answer_delete';
    public const ANSWER_CHANGE_QUESTION = 'answer_change_question';

    public const MEDIA_UPLOAD = 'media_upload';

    public const EXAM_SESSION_CREATE = 'exam_session_create';
    public const EXAM_SESSION_DELETE = 'exam_session_delete';
    public const EXAM_SESSION_FINISH = 'exam_session_finish';
    public const EXAM_SESSION_GRADE = 'exam_session_grade';
    public const EXAM_SESSION = 'exam_session';
    public const EXAM_SESSIONS = 'exam_sessions';

    public const EXAM_RESPONSE_CREATE = 'exam_response_create';
    public const EXAM_RESPONSE_UPDATE = 'exam_response_update';
    public const EXAM_RESPONSE_DELETE = 'exam_response_delete';
    public const EXAM_RESPONSE = 'exam_response';
    public const EXAM_RESPONSES = 'exam_responses';

    public const ANALYTICS_GRADES_CHART = 'analytics_grades_chart';

    public const ACTIVITY_TRACKINGS = 'activity_trackings';
}
