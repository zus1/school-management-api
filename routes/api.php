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
        Route::get('/grades', \App\Http\Controllers\Grade\RetrieveCollection::class)
            ->name(RouteName::GRADES);

        Route::post('/attendances', \App\Http\Controllers\Attendance\Create::class)
            ->name(RouteName::ATTENDANCE_CREATE);
        Route::put('/attendances/{attendance}', \App\Http\Controllers\Attendance\Update::class)
            ->name(RouteName::ATTENDANCE_UPDATE)
            ->where('attendance', '[0-9]+');
        Route::delete('/attendances/(attendance}', \App\Http\Controllers\Attendance\Delete::class)
            ->name(RouteName::ATTENDANCE_DELETE)
            ->where('grade', '[0-9]+');
        Route::get('/attendances', \App\Http\Controllers\Attendance\RetrieveCollection::class)
            ->name(RouteName::ATTENDANCES);
        Route::get('/attendances/aggregate', \App\Http\Controllers\Attendance\Aggregate::class)
            ->name(RouteName::ATTENDANCES_AGGREGATE);
    });



    Route::get('/me', \App\Http\Controllers\Me\Me::class)
        ->name(RouteName::ME);
    Route::put('/me', \App\Http\Controllers\Me\Update::class)
        ->name(RouteName::ME_UPDATE);
    Route::post('/me/avatar', \App\Http\Controllers\Me\Avatar::class)
        ->name(RouteName::ME_AVATAR);

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
