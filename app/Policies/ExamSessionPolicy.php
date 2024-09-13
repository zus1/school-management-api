<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;

class ExamSessionPolicy
{
    public function create(User $user, Exam $exam): bool
    {
        if(!$user instanceof Student || !$user->hasRole(Roles::STUDENT)) {
            return false;
        }

        /** @var SchoolClass $schoolClass */
        $schoolClass = $user->schoolClass()->first();

        return $exam->isSchoolClassAllowed($schoolClass->name);
    }

    public function delete(User $user, ExamSession $examSession): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        return $this->isTeacherExamOwner($user, $examSession);
    }

    public function retrieve(User $user, ExamSession $examSession): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        if($user->hasRole(Roles::TEACHER)) {
            /** @var Exam $exam */
            $exam = $examSession->exam()->first();

            return $user->id === $exam->teacher_id;
        }

        if($user->hasRole(Roles::STUDENT)) {
            return $user->id === $examSession->student_id;
        }

        if($user->hasRole(Roles::GUARDIAN) && $user instanceof Guardian) {
            $students = $user->students()->get();

            $allowed = false;
            /** @var Student $student */
            foreach ($students as $student) {
                if($student->id === $examSession->student_id) {
                    $allowed = true;

                    break;
                }
            }

            return $allowed;
        }

        return false;
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::ADMIN) ||
            $user->hasRole(Roles::TEACHER) ||
            $user->hasRole(Roles::STUDENT) ||
            $user->hasRole(Roles::GUARDIAN);
    }

    public function finish(User $user, ExamSession $examSession): bool
    {
        return $user->hasRole(Roles::STUDENT) && $user->id === $examSession->student_id;
    }

    public function grade(User $user, ExamSession $examSession): bool
    {
        return $this->isTeacherExamOwner($user, $examSession);
    }

    private function isTeacherExamOwner(User $user, ExamSession $examSession): bool
    {
        /** @var Exam $exam */
        $exam = $examSession->exam()->first();

        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }
}
