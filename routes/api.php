<?php

use App\Constant\RouteName;
use Illuminate\Support\Facades\Route;

Route::middleware('custom-auth')->group(function () {
    Route::middleware('custom-authorize')->group(function () {
        Route::post('/students', \App\Http\Controllers\Student\Create::class)
            ->name(RouteName::STUDENT_CREATE);
        Route::put('/students/{student}', \App\Http\Controllers\Student\Update::class)
            ->name(RouteName::STUDENT_UPDATE)
            ->where('student', '[0-9]+');
        Route::delete('/student/{student}', \App\Http\Controllers\Student\Delete::class)
            ->name(RouteName::STUDENT_DELETE)
            ->where('student', '[0-9]+');
        Route::get('/students', \App\Http\Controllers\Student\RetrieveCollection::class)
            ->name(RouteName::STUDENTS);
        Route::get('/students/{student}', \App\Http\Controllers\Student\Retrieve::class)
            ->name(RouteName::STUDENT)
            ->where('student', '[0-9]+');

        Route::put('/teachers/{teacher}', \App\Http\Controllers\Teacher\Update::class)
            ->name(RouteName::TEACHER_UPDATE)
            ->where('teacher', '[0-9]+');
        Route::delete('/teachers/{teacher}', \App\Http\Controllers\Teacher\Delete::class)
            ->name(RouteName::TEACHER_DELETE)
            ->where('teacher', '[0-9]+');
        Route::get('/teachers/{teacher}', \App\Http\Controllers\Teacher\Retrieve::class)
            ->name(RouteName::TEACHER)
            ->where('teacher', '[0-9]+');
        Route::get('/teachers', \App\Http\Controllers\Teacher\RetrieveCollection::class)
            ->name(RouteName::TEACHERS);

        Route::put('/guardians/{guardian}', \App\Http\Controllers\Guardian\Update::class)
            ->name(RouteName::GUARDIAN_UPDATE)
            ->where('guardian', '[0-9]+');
        Route::delete('/guardians/{guardian}', \App\Http\Controllers\Guardian\Delete::class)
            ->name(RouteName::GUARDIAN_DELETE)
            ->where('guardian', '[0-9]+');
        Route::get('/guardians/{guardian}', \App\Http\Controllers\Guardian\Retrieve::class)
            ->name(RouteName::GUARDIAN)
            ->where('guardian', '[0-9]+');
        Route::get('/guardians', \App\Http\Controllers\Guardian\RetrieveCollection::class)
            ->name(RouteName::GUARDIANS);

        Route::put('/users/{user}/active/{active}', \App\Http\Controllers\User\ToggleActive::class)
            ->name(RouteName::USER_TOGGLE_ACTIVE)
            ->where('guardian', '[0-9]+')
            ->where('active', 'true|false')
            ->middleware('inject-user-parent');

        Route::delete('/me', \App\Http\Controllers\Me\Delete::class)
            ->name(RouteName::ME_DELETE);

        Route::post('/calendars', \App\Http\Controllers\Calendar\Create::class)
            ->name(RouteName::CALENDAR_CREATE);
        Route::put('/calendars/{calendar}', \App\Http\Controllers\Calendar\Update::class)
            ->name(RouteName::CALENDAR_UPDATE)
            ->where('calendar', '[0-9]+');
        Route::delete('/calendars/{calendar}', \App\Http\Controllers\Calendar\Delete::class)
            ->name(RouteName::CALENDAR_DELETE)
            ->where('calendar', '[0-9]+');
        Route::get('/calendars', \App\Http\Controllers\Calendar\RetrieveCollection::class)
            ->name(RouteName::CALENDARS);
        Route::put('/calendars/{calendar}/active/{active}', \App\Http\Controllers\Calendar\ToggleActive::class)
            ->name(RouteName::CALENDAR_TOGGLE_ACTIVE)
            ->where('calendar', '[0-9]+')
            ->where('active', 'true|false');

        Route::post('/events/{calendar}', \App\Http\Controllers\Event\Create::class)
            ->name(RouteName::EVENT_CREATE)
            ->where('calendar', '[0-9]+');
        Route::put('/events/{event}', \App\Http\Controllers\Event\Update::class)
            ->name(RouteName::EVENT_UPDATE)
            ->where('event', '[0-9]+')
            ->middleware('inject-event');
        Route::delete('/events/{event}', \App\Http\Controllers\Event\Delete::class)
            ->name(RouteName::EVENT_DELETE)
            ->where('event', '[0-9]+')
            ->middleware('inject-event');
        Route::get('/events/calendar/{calendar}', \App\Http\Controllers\Event\RetrieveCollection::class)
            ->name(RouteName::EVENTS)
            ->where('calendar', '[0-9]+');
        Route::get('/events/{event}', \App\Http\Controllers\Event\Retrieve::class)
            ->name(RouteName::EVENT)
            ->where('event', '[0-9]+')
            ->middleware('inject-event');
        Route::put('/events/{event}/status', \App\Http\Controllers\Event\UpdateStatus::class)
            ->name(RouteName::EVENT_UPDATE_STATUS)
            ->where('event', '[0-9]+')
            ->middleware('inject-event-parent');
        Route::put('/events/{event}/repeatable-status', \App\Http\Controllers\Event\UpdateRepeatableStatus::class)
            ->name(RouteName::EVENT_UPDATE_REPEATABLE_STATUS)
            ->where('event', '[0-9]+')
            ->middleware('inject-event-parent');
        Route::put('/events/{event}/toggle-notify/{user}/{action}', \App\Http\Controllers\Event\ToggleNotify::class)
            ->name(RouteName::EVENT_TOGGLE_NOTIFY)
            ->where('event', '[0-9]+')
            ->where('user', '[0-9]+')
            ->where('action', 'add|remove')
            ->middleware(['inject-event-parent', 'inject-user-parent']);

        Route::post('/subjects', \App\Http\Controllers\Subject\Create::class)
            ->name(RouteName::SUBJECT_CREATE);
        Route::put('/subjects/{subject}', \App\Http\Controllers\Subject\Update::class)
            ->name(RouteName::SUBJECT_UPDATE)
            ->where('subject', '[0-9]+');
        Route::delete('/subjects/{subject}', \App\Http\Controllers\Subject\Delete::class)
            ->name(RouteName::SUBJECT_DELETE)
            ->where('subject', '[0-9]+');
        Route::put('/subjects/{subject}/teachers/{teacher}', \App\Http\Controllers\Subject\ToggleLecturer::class)
            ->name(RouteName::SUBJECT_TOGGLE_LECTURER)
            ->where('subject', '[0-9]+')
            ->where('teacher', '[0-9]+');
        Route::put('/subjects/{subject}/teachers/{teacher}/school-classes', \App\Http\Controllers\Subject\ToggleLecturerClasses::class)
            ->name(RouteName::SUBJECT_TOGGLE_LECTURER_CLASSES)
            ->where('subject', '[0-9]+')
            ->where('teacher', '[0-9]+');

        Route::get('teacher-subjects/{teacherSubject}', \App\Http\Controllers\TeacherSubject\Retrieve::class)
            ->name(RouteName::TEACHER_SUBJECT)
            ->where('teacherSubject', '[0-9]+');
        Route::get('/teacher-subjects', \App\Http\Controllers\TeacherSubject\RetrieveCollection::class)
            ->name(RouteName::TEACHER_SUBJECTS);

        Route::post('/classrooms',  \App\Http\Controllers\Classroom\Create::class)
            ->name(RouteName::CLASSROOM_CREATE);
        Route::put('/classrooms/{classroom}', \App\Http\Controllers\Classroom\Update::class)
            ->name(RouteName::CLASSROOM_UPDATE)
            ->where('classroom', '[0-9]+');
        Route::delete('/classrooms/{classroom}', \App\Http\Controllers\Classroom\Delete::class)
            ->name(RouteName::CLASSROOM_DELETE)
            ->where('classroom', '[0-9]+');
        Route::get('/classrooms', \App\Http\Controllers\Classroom\RetrieveCollection::class)
            ->name(RouteName::CLASSROOMS);
        Route::get('/classrooms/{classroom}', \App\Http\Controllers\Classroom\Retrieve::class)
            ->name(RouteName::CLASSROOM)
            ->where('classroom', '[0-9]+');
        Route::put('/classrooms/{classroom}/equipments/{equipment}', \App\Http\Controllers\Classroom\ToggleEquipment::class)
            ->name(RouteName::CLASSROOM_TOGGLE_EQUIPMENT)
            ->where('classroom', '[0-9]+')
            ->where('equipment', '[0-9]+');
        Route::put(
            '/classrooms/{classroom}/equipments/{equipment}/quantity',
            \App\Http\Controllers\Classroom\UpdateEquipmentQuantity::class
        )->name(RouteName::CLASSROOM_UPDATE_EQUIPMENT_QUANTITY)
            ->where('classroom', '[0-9]+')
            ->where('equipment', '[0-9]+');

        Route::post('/equipments', \App\Http\Controllers\Equipment\Create::class)
            ->name(RouteName::EQUIPMENT_CREATE);
        Route::put('/equipments/{equipment}', \App\Http\Controllers\Equipment\Update::class)
            ->name(RouteName::EQUIPMENT_UPDATE)
            ->where('equipment', '[0-9]+');
        Route::delete('/equipments/{equipment}', \App\Http\Controllers\Equipment\Delete::class)
            ->name(RouteName::EQUIPMENT_DELETE)
            ->where('equipment', '[0-9]+');
        Route::get('/equipments', \App\Http\Controllers\Equipment\RetrieveCollection::class)
            ->name(RouteName::EQUIPMENTS);
        Route::get('/equipments/{equipment}', \App\Http\Controllers\Equipment\Retrieve::class)
            ->name(RouteName::EQUIPMENT)
            ->where('equipment', '[0-9]+');

        Route::post('/messages/recipient/{recipient}', \App\Http\Controllers\Message\Create::class)
            ->name(RouteName::MESSAGE_CREATE)
            ->where('recipient', '[0-9]+')
            ->middleware('inject-message-recipient');
        Route::put('/messages/{message}', \App\Http\Controllers\Message\Update::class)
            ->name(RouteName::MESSAGE_UPDATE)
            ->where('message', '[0-9]+');
        Route::delete('/messages/{message}', \App\Http\Controllers\Message\Delete::class)
            ->name(RouteName::MESSAGE_DELETE)
            ->where('message', '[0-9]+');
        Route::get('/messages', \App\Http\Controllers\Message\RetrieveCollection::class)
            ->name(RouteName::MESSAGES);
        Route::get('/messages/{message}', \App\Http\Controllers\Message\Retrieve::class)
            ->name(RouteName::MESSAGE)
            ->where('message', '[0-9]+');
        Route::put('/messages/mark-as-read', \App\Http\Controllers\Message\MarkAsRead::class)
            ->name(RouteName::MESSAGES_MARK_AS_READ);

        Route::post('/grades', \App\Http\Controllers\Grade\Create::class)
            ->name(RouteName::GRADE_CREATE);
        Route::put('/grades/{grade}', \App\Http\Controllers\Grade\Update::class)
            ->name(RouteName::GRADE_UPDATE)
            ->where('grade', '[0-9]+');
        Route::delete('/grades/{grade}', \App\Http\Controllers\Grade\Delete::class)
            ->name(RouteName::GRADE_DELETE)
            ->where('grade', '[0-9]+');
        Route::get('/grades', \App\Http\Controllers\Grade\RetrieveCollectionCustom::class)
            ->name(RouteName::GRADES);

        Route::post('/attendances', \App\Http\Controllers\Attendance\Create::class)
            ->name(RouteName::ATTENDANCE_CREATE);
        Route::put('/attendances/{attendance}', \App\Http\Controllers\Attendance\Update::class)
            ->name(RouteName::ATTENDANCE_UPDATE)
            ->where('attendance', '[0-9]+');
        Route::delete('/attendances/(attendance}', \App\Http\Controllers\Attendance\Delete::class)
            ->name(RouteName::ATTENDANCE_DELETE)
            ->where('grade', '[0-9]+');
        Route::get('/attendances', \App\Http\Controllers\Attendance\RetrieveCollectionCustom::class)
            ->name(RouteName::ATTENDANCES);
        Route::get('/attendances/aggregate', \App\Http\Controllers\Attendance\Aggregate::class)
            ->name(RouteName::ATTENDANCES_AGGREGATE);

        Route::post('/grading-rules', \App\Http\Controllers\GradingRule\Create::class)
            ->name(RouteName::GRADING_RULE_CREATE);
        Route::put('/grading-rules/{gradingRule}', \App\Http\Controllers\GradingRule\Update::class)
            ->name(RouteName::GRADING_RULE_UPDATE)
            ->where('gradingRule', '[0-9]+');
        Route::delete('/grading-rules/{gradingRule}', \App\Http\Controllers\GradingRule\Delete::class)
            ->name(RouteName::GRADING_RULE_DELETE)
            ->where('gradingRule', '[0-9]+');
        Route::get('/grading-rules', \App\Http\Controllers\GradingRule\RetrieveCollection::class)
            ->name(RouteName::GRADING_RULES);
        Route::get('/grading-rules/{gradingRule}', \App\Http\Controllers\GradingRule\Retrieve::class)
            ->name(RouteName::GRADING_RULE)
            ->where('gradingRule', '[0-9]+');

        Route::post('/grade-ranges/{gradingRule}', \App\Http\Controllers\GradeRange\Create::class)
            ->name(RouteName::GRADE_RANGE_CREATE)
            ->where('gradingRule', '[0-9]+');
        Route::put('/grade-ranges/{gradeRange}', \App\Http\Controllers\GradeRange\Update::class)
            ->name(RouteName::GRADE_RANGE_UPDATE)
            ->where('gradeRange', '[0-9]+');
        Route::delete('/grade-ranges/{gradeRange}', \App\Http\Controllers\GradeRange\Delete::class)
            ->name(RouteName::GRADE_RANGE_DELETE)
            ->where('gradeRange', '[0-9]+');

        Route::post('/exams', \App\Http\Controllers\Exam\Create::class)
            ->name(RouteName::EXAM_CREATE);
        Route::put('/exams/{exam}', \App\Http\Controllers\Exam\Update::class)
            ->name(RouteName::EXAM_UPDATE)
            ->where('exam', '[0-9]+');
        Route::delete('/exams/{exam}', \App\Http\Controllers\Exam\Delete::class)
            ->name(RouteName::EXAM_DELETE)
            ->where('exam', '[0-9]+');
        Route::get('/exams', \App\Http\Controllers\Exam\RetrieveCollection::class)
            ->name(RouteName::EXAMS);
        Route::get('/exams/{exam}', \App\Http\Controllers\Exam\Retrieve::class)
            ->name(RouteName::EXAM)
            ->where('exam', '[0-9]+');
        Route::put('/exams/{exam}/grading-rules/{gradingRule?}', \App\Http\Controllers\Exam\ToggleGradingRule::class)
            ->name(RouteName::EXAM_TOGGLE_GRADING_RULE)
            ->where('exam', '[0-9]+')
            ->where('gradingRule', '[0-9]+');
        Route::put('/exams/{exam}/school-classes/{schoolClass}/allowed', \App\Http\Controllers\Exam\ToggleAllowedSchoolClass::class)
            ->name(RouteName::EXAM_TOGGLE_ALLOWED_SCHOOL_CLASS)
            ->where('exam', '[0-9]+')
            ->where('schoolClass', '{[0-9]+}{[a-z]+}');

        Route::post('/questions/{exam}', \App\Http\Controllers\Question\CreateBulk::class)
            ->name(RouteName::QUESTIONS_CREATE)
            ->where('exam', '[0-9]+');
        Route::put('/questions/{question}', \App\Http\Controllers\Question\Update::class)
            ->name(RouteName::QUESTION_UPDATE)
            ->where('question', '[0-9]+');
        Route::delete('/questions/{question}', \App\Http\Controllers\Question\Delete::class)
            ->name(RouteName::QUESTION_DELETE)
            ->where('question', '[0-9]+');
        Route::get('/questions/exams/{exam}', \App\Http\Controllers\Question\RetrieveCollection::class)
            ->name(RouteName::QUESTIONS)
            ->where('exam', '[0-9]+');
        Route::get('/question/{question}', \App\Http\Controllers\Question\Retrieve::class)
            ->name(RouteName::QUESTION)
            ->where('question', '[0-9]+');
        Route::put('/questions/{question}/exams/{exam}', \App\Http\Controllers\Question\ChangeExam::class)
            ->name(RouteName::QUESTION_CHANGE_EXAM)
            ->where('question', '[0-9]+')
            ->where('exam', '[0-9]+');

        Route::put('/answers/{answer}', \App\Http\Controllers\Answer\Update::class)
            ->name(RouteName::ANSWER_UPDATE)
            ->where('answer', '[0-9]+');
        Route::delete('/answers/{answer}', \App\Http\Controllers\Answer\Delete::class)
            ->name(RouteName::ANSWER_DELETE)
            ->where('answer', '[0-9]+');
        Route::put('/answers/{answer}/questions/{question}', \App\Http\Controllers\Answer\ChangeQuestion::class)
            ->name(RouteName::ANSWER_CHANGE_QUESTION)
            ->where('question', '[0-9]+')
            ->where('answer', '[0-9]+');

        Route::post('/medias/{owner?}', \App\Http\Controllers\Media\Upload::class)
            ->name(RouteName::MEDIA_UPLOAD)
            ->where('owner', '[0-9]+')
            ->middleware('inject-media-owner');

        Route::post('/exam-sessions/{exam}', \App\Http\Controllers\ExamSession\Create::class)
            ->name(RouteName::EXAM_SESSION_CREATE)
            ->where('exam', '[0-9]+');
        Route::delete('/exam-sessions/{examSession}', \App\Http\Controllers\ExamSession\Delete::class)
            ->name(RouteName::EXAM_SESSION_DELETE)
            ->where('examSession', '[0-9]+');
        Route::get('/exam-sessions/{examSession}', \App\Http\Controllers\ExamSession\Retrieve::class)
            ->name(RouteName::EXAM_SESSION)
            ->where('examSession', '[0-9]+');
        Route::get('/exam-sessions/exam/{exam}', \App\Http\Controllers\ExamSession\RetrieveCollection::class)
            ->name(RouteName::EXAM_SESSIONS)
            ->where('exam', '[0-9]+');
        Route::put('/exam-session/{examSession}/finish', \App\Http\Controllers\ExamSession\Finish::class)
            ->name(RouteName::EXAM_SESSION_FINISH)
            ->where('examSession', '[0-9]+');
        Route::post('/exam-sessions/{examSession}/grade', \App\Http\Controllers\ExamSession\Grade::class)
            ->name(RouteName::EXAM_SESSION_GRADE)
            ->where('examSession', '[0-9]+');

        Route::post('/exam-responses/{examSession}', \App\Http\Controllers\ExamResponse\Create::class)
            ->name(RouteName::EXAM_RESPONSE_CREATE)
            ->where('examSession', '[0-9]+');
        Route::put('/exam-responses/{examResponse}', \App\Http\Controllers\ExamResponse\Update::class)
            ->name(RouteName::EXAM_RESPONSE_UPDATE)
            ->where('examResponse', '[0-9]+');
        Route::delete('/exam-responses/{examResponse}', \App\Http\Controllers\ExamResponse\Delete::class)
            ->name(RouteName::EXAM_RESPONSE_DELETE)
            ->where('examResponse', '[0-9]+');
        Route::get('/exam-responses/exam-sessions/{examSession}', \App\Http\Controllers\ExamResponse\RetrieveCollection::class)
            ->name(RouteName::EXAM_RESPONSES)
            ->where('examSession', '[0-9]+');
        Route::get('exam-responses/{examResponse}', \App\Http\Controllers\ExamResponse\Retrieve::class)
            ->name(RouteName::EXAM_RESPONSE)
            ->where('examResponse', '[0-9]+');

        Route::get('/analytics/grades-chart', \App\Http\Controllers\Grade\GradesChart::class)
            ->name(RouteName::ANALYTICS_GRADES_CHART);
    });

    Route::get('/grades/top-average', \App\Http\Controllers\Grade\TopAverage::class)
        ->name(RouteName::GRADES_TOP_AVERAGE);

    Route::get('/me', \App\Http\Controllers\Me\Me::class)
        ->name(RouteName::ME);
    Route::put('/me', \App\Http\Controllers\Me\Update::class)
        ->name(RouteName::ME_UPDATE);

    Route::post('/school-classes', \App\Http\Controllers\SchoolClass\Create::class)
        ->name(RouteName::SCHOOL_CLASS_CREATE);
    Route::put('/school-classes/{schoolClass}', \App\Http\Controllers\SchoolClass\Update::class)
        ->name(RouteName::SCHOOL_CLASS_UPDATE)
        ->where('schoolClass', '[0-9]+');
    Route::delete('/school-classes/{schoolClass}', \App\Http\Controllers\SchoolClass\Delete::class)
        ->name(RouteName::SCHOOL_CLASS_DELETE)
        ->where('schoolClass', '[0-9]+');
});

Route::post('/students/onboarding', \App\Http\Controllers\Student\Onboard::class)
    ->name(RouteName::STUDENT_ONBOARDING);

Route::prefix('auth')->group(function () {
    Route::post('/register', \App\Http\Controllers\Auth\Register::class)
        ->name(RouteName::AUTH_REGISTER);
    Route::get('/verify-phone', \App\Http\Controllers\Auth\VerifyPhone::class)
        ->name(RouteName::AUTH_VERIFY_PHONE);
});
