<?php

namespace App\Repository;

use App\Constant\Roles;
use App\Interface\CanUpdateUserInterface;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Zus1\LaravelAuth\Helper\TokenHelper;

class StudentRepository extends UserRepository implements CanUpdateUserInterface
{
    public function __construct(
        private TokenHelper $tokenHelper,
        private TuitionRepository $tuitionRepository,
    ){
    }

    protected const MODEL = Student::class;

    public function create(array $data): Student
    {
        $student = new Student();
        $student->email = $data['email'];
        $student->password = Hash::make($this->tokenHelper->getToken(50));
        $student->phone = $data['phone'];
        $student->phone_verified = true;
        $student->active = true;
        $student->roles = [Roles::STUDENT];

        $this->associateSchoolClass($student);

        $student->save();

        $this->tuitionRepository->createUnpaid($student);

        return $student;
    }

    public function update(array $data, Student|User $user): Student
    {
        $this->updateBaseProperties($user, $data);

        $user->onboarded_at === '' ?
            $user->onboarded_at = Carbon::now()->format('Y-m-d H:i:s') :
            $user->last_change_at = Carbon::now()->format('Y-m-d H:i:s');

        $user->save();

        return $user;
    }

    public function associateGuardian(Guardian $guardian, int $id): Student
    {
        /** @var Student $student */
        $student = $this->findOneByOr404(['id' => $id]);
        $student->guardian()->associate($guardian);
        $student->save();

        return $student;
    }

    public function handleYearlyTuitionCreate(int $chunkSize, array $callback): void
    {
        $this->getBuilder()->whereRelation('parent','active', true)
            ->with('guardian')
            ->chunk($chunkSize, $callback);
    }

    private function associateSchoolClass(Student $student): void
    {
        /** @var Teacher $teacher */
        $teacher = Auth::user();
        $schoolClass = $teacher->schoolClass()->first();


        $student->schoolClass()->associate($schoolClass);
    }
}
