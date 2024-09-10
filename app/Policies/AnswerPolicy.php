<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;

class AnswerPolicy
{
    public function update(User $user, Answer $answer): bool
    {
        return $this->isExamOwner($user, $answer);
    }

    public function delete(User $user, Answer $answer): bool
    {
        return $this->isExamOwner($user, $answer);
    }

    public function changeQuestion(User $user, Answer $answer, Question $question): bool
    {
        /** @var Exam $newExam */
        $newExam = $question->exam()->first();

        return $this->isExamOwner($user, $answer) && $this->isExamOwner($user, $question);
    }

    private function isExamOwner(User $user, Answer|Question $subject): bool
    {
        /** @var Exam $exam */
        $exam = $subject->exam()->first();

        return $user->hasRole(Roles::TEACHER) && $user->id === $exam->teacher_id;
    }
}
