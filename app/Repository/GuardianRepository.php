<?php

namespace App\Repository;

use App\Constant\Roles;
use App\Interface\CanRegisterUserInterface;
use App\Interface\CanUpdateUserInterface;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GuardianRepository extends UserRepository implements CanRegisterUserInterface, CanUpdateUserInterface
{
    protected const MODEL = Guardian::class;

    public function __construct(
        private StudentRepository $studentRepository,
    ){
    }

    public function register(array $data): User
    {
        $guardian = new Guardian();
        $this->modifySharedProperties($guardian, $data);
        $guardian->roles = [Roles::GUARDIAN];

        $this->registerBaseProperties($guardian, $data);

        $guardian->save();

        $this->associateWithStudents($guardian, $data['student_ids']);

        return $guardian;
    }

    private function associateWithStudents(Guardian $guardian, array $studentIds): void
    {
        array_walk($studentIds, function (int $studentId) use ($guardian) {
            $this->studentRepository->associateGuardian($guardian, $studentId);
        });
    }

    public function update(array $data, Guardian|User $user): Guardian
    {
        /** @var User $auth */
        $auth = Auth::user();

        $this->modifySharedProperties($user, $data);

        if($auth->hasOneOfRoles([Roles::GUARDIAN, Roles::ADMIN])) {
            $this->updateBaseProperties($user, $data);
        }

        $user->save();

        return $user;
    }

    public function delete(): void
    {
        /** @var Guardian $auth */
        $auth = Auth::user();

        $auth->students()->update(['guardian_id' => null]);

        $auth->delete();
    }

    private function modifySharedProperties(Guardian $guardian, array $data): void
    {
        $guardian->street = sprintf('%s %s', $data['street'], $data['street_number']);
        $guardian->occupation = $data['occupation'];
        $guardian->city = $data['city'];
    }
}
