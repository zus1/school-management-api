<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Guardian;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Repository\UserRepository;

class PaymentPolicy
{
    public function __construct(
        private UserRepository $userRepository,
    ){
    }

    public function before(User $user): ?bool
    {
        return $user->hasRole(Roles::ADMIN) ? true : null;
    }

    public function collection(User $user): bool
    {
        return $user->hasRole(Roles::TEACHER) ||
            $user->hasRole(Roles::GUARDIAN) ||
            $user->hasRole(Roles::STUDENT);
    }

    public function retrieve(User $user, Payment $payment): bool
    {
        $parent = $this->userRepository->findParentByChild($user);

        $isOwner = $parent->id === $payment->user_id;

        if($user->hasRole(Roles::TEACHER) && $user instanceof Teacher) {
            return $isOwner === true ||
                $this->isPassedByOwnedStudent($user, $payment) ||
                $this->isPassedByStudentsGuardian($user, $payment);
        }

        return $isOwner;
    }

    private function isPassedByOwnedStudent(Teacher $user, Payment $payment): bool
    {
        $pass = false;

        $students = $user->students()->with('parent')->get();
        /** @var Student $student */
        foreach ($students as $student) {
            if($student->parent->id === $payment->id) {
                $pass = true;

                break;
            }
        }

        return $pass;
    }

    private function isPassedByStudentsGuardian(Teacher $user, Payment $payment): bool
    {
        $pass = false;

        $guardians = $user->guardians()->with('parent')->get();
        /** @var Guardian $guardian */
        foreach($guardians as $guardian) {
            if($guardian->parent->id === $payment->user_id) {
                $pass = true;

                break;
            }
        }

        return $pass;
    }
}
