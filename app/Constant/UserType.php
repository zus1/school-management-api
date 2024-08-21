<?php

namespace App\Constant;

use App\Repository\GuardianRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserType extends Constant
{
    public final const TEACHER = 'teacher';
    public final const STUDENT = 'student';
    public final const GUARDIAN = 'guardian';
    public final const ADMIN = 'god';

    public static function repositoryClass(string $type): string
    {
        return match ($type) {
            self::TEACHER => TeacherRepository::class,
            self::STUDENT => StudentRepository::class,
            self::GUARDIAN => GuardianRepository::class,
            self::ADMIN => throw new HttpException(500, 'Gods do not need repository'),
            default => throw new HttpException(500, 'Could not find user with type '.$type),
        };
    }
}
