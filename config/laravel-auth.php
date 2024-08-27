<?php

use App\Constant\CustomTokenType;
use App\Constant\RouteName;
use Zus1\LaravelAuth\Constant\Token\TokenType;

return [
    'user_namespace' => env('LARAVEL_AUTH_USER_NAMESPACE', 'App\Models'),
    'user_class' => sprintf(
        '%s\\%s',
        env('LARAVEL_AUTH_USER_NAMESPACE', 'App\Models'),
        env('LARAVEL_AUTH_USER_CLASS', 'User')
    ),

    'token' => [
        'expires_in' => [
            'access_token' => 30 * 24 * 60,
            'refresh_token' => 60 * 24 * 60,
            'user_verification_token' => 60,
            'reset_password_token' => 30,
        ],
        'length' => [
            'access_token' => 100,
            'refresh_token' => 100,
            'user_verification_token' => 50,
            'reset_password_token' => 50,
        ],
        'type_class' => CustomTokenType::class,
        'request_header' => 'Authorization'
    ],
    'email' => [
        'subject' => [
            'verification' => 'Email verification',
            'reset_password' => 'Reset Password',
            'welcome' => 'Welcome',
            'invitation' => 'Student onboarding'
        ],
        'templates' => [
            'verification' => [
                'txt' => 'mail/authentication::verify-txt',
                'markdown' => 'mail/authentication::verify'
            ],
            'reset_password' => [
                'txt' => 'mail/authentication::reset-password-txt',
                'markdown' => 'mail/authentication::reset-password'
            ],
            'welcome' => [
                'txt' => 'mail/authentication::welcome-txt',
                'markdown' => 'mail/authentication::welcome'
            ],
            'invitation' => [
                'txt' => 'auth.invitation-txt',
                'markdown' => 'auth.invitation'
            ],
        ],
        'redirect_url' => [
            'invitation' => env('AUTH_INVITATION_REDIRECT_URL'),
        ],
    ],

    'authorization' => [
        'mapping' => [
            RouteName::STUDENT_CREATE => 'create',
            RouteName::STUDENT_UPDATE => 'update',
            RouteName::STUDENT_DELETE => 'delete',
            RouteName::STUDENT => 'retrieve',
            RouteName::STUDENTS => 'collection',
            RouteName::TEACHER_UPDATE => 'pass',
            RouteName::TEACHER_DELETE => 'pass',
            RouteName::TEACHER => 'pass',
            RouteName::TEACHERS => 'pass',
            RouteName::GUARDIAN_UPDATE => 'update',
            RouteName::GUARDIAN_DELETE => 'delete',
            RouteName::GUARDIAN => 'retrieve',
            RouteName::GUARDIANS => 'collection',
            RouteName::USER_TOGGLE_ACTIVE => 'toggleActive',
            RouteName::ME_DELETE => 'meDelete',
            RouteName::CALENDAR_CREATE => 'pass',
            RouteName::CALENDAR_UPDATE => 'pass',
            RouteName::CALENDAR_DELETE => 'pass',
            RouteName::CALENDAR_TOGGLE_ACTIVE => 'pass',
            RouteName::CALENDARS => 'pass',
            RouteName::EVENT_CREATE => 'create',
            RouteName::EVENT_UPDATE => 'update',
            RouteName::EVENT_DELETE => 'delete',
            RouteName::EVENT => 'retrieve',
            RouteName::EVENTS => 'collection',
            RouteName::EVENT_TOGGLE_NOTIFY => 'toggleNotify',
            RouteName::EVENT_UPDATE_STATUS => 'updateStatus',
            RouteName::EVENT_UPDATE_REPEATABLE_STATUS => 'updateRepeatableStatus',
        ],
        'possible_route_parameters' => [
            'student', 'teacher', 'guardian', 'user', 'calendar', 'event',
        ],
        'additional_subjects' => [
            RouteName::STUDENT_CREATE => \App\Models\Student::class,
            RouteName::STUDENTS => \App\Models\Student::class,
            RouteName::TEACHERS => \App\Models\Teacher::class,
            RouteName::GUARDIANS => \App\Models\Guardian::class,
            RouteName::ME_DELETE => \App\Models\Guardian::class,
            RouteName::CALENDARS => \App\Models\Calendar::class,
            RouteName::EVENTS => \App\Models\Event::class,
        ],
        'subject_overrides' => [
            RouteName::ME_DELETE => \App\Models\Guardian::class
        ],
    ]
];
