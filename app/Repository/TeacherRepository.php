<?php

namespace App\Repository;

use App\Constant\Roles;
use App\Interface\CanRegisterUserInterface;
use App\Interface\CanUpdateUserInterface;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeacherRepository extends UserRepository implements CanRegisterUserInterface, CanUpdateUserInterface
{
    protected const MODEL = Teacher::class;

    public function register(array $data): Teacher
    {
        $teacher = new Teacher();
        $this->modifySharedFields($teacher, $data);
        $teacher->roles = [Roles::TEACHER];

        $this->registerBaseProperties($teacher, $data);

        $teacher->save();

        return $teacher;
    }

    public function update(array $data, Teacher|User $user): Teacher
    {
        /** @var User $auth */
        $auth = Auth::user();

        $user->social_security_number = $data['social_security_number'];
        $this->updateBaseProperties($user, $data);

        if($auth->hasRole(Roles::ADMIN)) {
            $this->modifySharedFields($user, $data);
        }

        $user->save();

        return $user;
    }

    private function modifySharedFields(Teacher $teacher, array $data): void
    {
        $teacher->months_of_employment = $data['months_of_employment'];
        $teacher->employed_at = $data['employed_at'];
        $teacher->social_security_number = $data['social_security_number'];
        $teacher->employment_ends_at = (new Carbon($data['employed_at']))
            ->addYears(Teacher::CONTRACT_DURATION)
            ->format('Y-m-d');
    }
}
