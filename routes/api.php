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
        Route::delete('/student', \App\Http\Controllers\Student\Delete::class)
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
