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
}
