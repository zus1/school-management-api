<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelAuth\Models\Token;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class UserRepository extends LaravelBaseRepository
{
    protected const MODEL = User::class;

    public function findByToken(Token $token): User
    {
        /** @var ?User $user */
        $user = $token->user()->first();

        if($user === null) {
            throw new HttpException(404, 'User not found');
        }

        return $user;
    }

    public function findChildByToken(Token $token): User
    {
        $user = $this->findByToken($token);

        return $user->child()->first();
    }


    public function verifyPhone(User $user): User
    {
        $user->phone_verified = true;

        $user->save();

        return $user;
    }

    public function toggleActive(User $user, bool $active): User
    {
        $user->active = $active;

        $user->save();

        return $user;
    }

    protected function registerBaseProperties(User $user, array $data): void
    {
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->dob = $data['dob'];
        $user->phone = $data['phone'];
        $user->gender = $data['gender'];
        $user->phone_verified = false;
        $user->active = false;
    }

    protected function updateBaseProperties(User $user, array $data): void
    {
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->dob = $data['dob'];
        $user->gender = $data['gender'];

        if(isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
    }
}
