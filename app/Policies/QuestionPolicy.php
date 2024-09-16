<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Exam;
use App\Models\Question;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;

class QuestionPolicy
{
    public function create(User $user, Exam $exam): bool
    {
        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }

    public function update(User $user, Question $question): bool
    {
        /** @var Exam $exam */
        $exam = $question->exam()->first();

        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }

    public function delete(User $user, Question $question): bool
    {
        /** @var Exam $exam */
        $exam = $question->exam()->first();

        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }

    public function collection(User $user, Exam $exam): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        if($user->hasRole(Roles::TEACHER)) {
            return $user->id === $exam->teacher_id;
        }

        if($user->hasRole(Roles::STUDENT) && $user instanceof Student) {
            /** @var SchoolClass $schoolClass */
            $schoolClass = $user->schoolClass()->first();

            return $user->hasSubject($exam->subject()->first()) &&
                $user->hasLecturer($exam->teacher()->first()) &&
                $exam->isSchoolClassAllowed($schoolClass->name);
        }

        return false;
    }

    public function retrieve(User $user, Question $question): bool
    {
        if($user->hasRole(Roles::ADMIN)) {
            return true;
        }

        /** @var Exam $exam */
        $exam = $question->exam()->first();

        if($user->hasRole(Roles::TEACHER)) {
            return $user->id === $exam->teacher_id;
        }

        if($user->hasRole(Roles::STUDENT) && $user instanceof Student) {
            return $user->hasSubject($exam->subject()->first()) && $user->hasLecturer($exam->teacher()->first());
        }

        return false;
    }

    public function changeExam(User $user, Question $question, Exam $exam): bool
    {
        /** @var Exam $currentExam */
        $currentExam = $question->exam()->first();

        return $user->hasRole(Roles::TEACHER) &&
            $user->id === $currentExam->teacher_id &&
            $user->id = $exam->teacher_id;
    }
}
