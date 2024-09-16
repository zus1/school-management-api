<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Exam;
use App\Models\ExamResponse;
use App\Models\ExamSession;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;

class ExamResponsePolicy
{
    public function create(User $user, ExamSession $examSession): bool
    {
        return $user->hasRole(Roles::STUDENT) && $user->id === $examSession->student_id;
    }

    public function update(User $user, ExamResponse $examResponse): bool
    {
        /** @var ExamSession $examSession */
        $examSession = $examResponse->examSession()->first();

        return $user->hasRole(Roles::STUDENT) && $user->id === $examSession->student_id;
    }

    public function collection(User $user, ExamSession $examSession): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        if($user->hasRole(Roles::STUDENT)) {
            return $user->id === $examSession->student_id;
        }

        if($user->hasRole(Roles::TEACHER)) {
            return $this->isTeacherExamOwner($user, $examSession);
        }

        if($user->hasRole(Roles::GUARDIAN)) {
            return $this->doesExamSessionBelongToGuardiansStudents($user, $examSession);
        }

        return false;
    }

    public function retrieve(User $user, ExamResponse $examResponse): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        if($user->hasRole(Roles::TEACHER)) {
            return $this->isTeacherExamOwner($user, $examResponse);
        }

        /** @var ExamSession $examSession */
        $examSession = $examResponse->examSession()->first();

        if($user->hasRole(Roles::STUDENT)) {
            return $user->id === $examSession->student_id;
        }

        if($user->hasRole(Roles::GUARDIAN)) {

            return $this->doesExamSessionBelongToGuardiansStudents($user, $examSession);
        }

        return false;
    }

    private function isTeacherExamOwner(User $user, ExamSession|ExamResponse $examSession): bool
    {
        /** @var Exam $exam */
        $exam = $examSession->exam()->first();

        return $user->id === $exam->teacher_id;
    }

    private function doesExamSessionBelongToGuardiansStudents(User $user, ExamSession $examSession): bool
    {
        if(!$user instanceof Guardian) {
            return false;
        }

        $students = $user->students()->get();

        $allowed = false;
        /** @var Student $student */
        foreach ($students as $student) {
            if(($allowed = $student->id === $examSession->student_id) === true) {
                break;
            }
        }

        return $allowed;
    }

    public function delete(User $user, ExamResponse $examResponse): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        /** @var Exam $exam */
        $exam = $examResponse->exam()->first();

        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }
}
