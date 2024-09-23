<?php

namespace App\Repository;

use App\Models\User;
use App\Trait\CanActivateModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelAuth\Models\Token;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class UserRepository extends LaravelBaseRepository
{
    use CanActivateModel;

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

    public function findAuthParent(): User
    {
        /** @var User $child */
        $child = Auth::user();

        return $child->parent()->first();
    }

    public function verifyPhone(User $user): User
    {
        $user->phone_verified = true;

        $user->save();

        return $user;
    }

    public function findByChildId(int $childId): User
    {
        $builder = $this->getBuilder();

        $user = $builder->whereMorphRelation('child', 'id', $childId)->first();

        if($user === null) {
            /** @var User $user */
            $user = $this->findOneByOr404(['id' => $childId]);

            return $user;
        }

        return $user;
    }

    public function findChildByParent(User $parent): User
    {
        try {
            $child = $parent->child()->first();
        } catch (ModelNotFoundException) {
            return $parent; //No child, only if parent is admin
        }

        return $child;
    }

    public function findParentByChild(User $child): User
    {
        try {
            $parent = $child->parent()->first();
        } catch (ModelNotFoundException) {
            return $child; //No parent, only if child is admin
        }

        return $parent;
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
