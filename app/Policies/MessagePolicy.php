<?php

namespace App\Policies;

use App\Constant\Roles;
use App\Models\Message;
use App\Models\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class MessagePolicy
{
    private User $auth;

    public function __construct(
        private MessageRepository $repository,
        private Request $request,
        UserRepository $userRepository,
    ){
        $this->auth = $userRepository->findAuthParent();
    }

    public function create(User $user, User $recipient): bool
    {
        $user = $this->auth;

        return !($user->hasRole(Roles::STUDENT) && $recipient->hasRole(Roles::STUDENT));
    }

    public function update(User $user, Message $message): bool
    {
        return $this->auth->id === $message->sender_id;
    }

    public function delete(User $user): bool
    {
        return $this->auth->hasRole(Roles::ADMIN);
    }

    public function retrieve(User $user, Message $message): bool
    {
        return $this->auth->id === $message->sender_id || $this->auth->id === $message->recipient_id;
    }

    public function collection(): bool
    {
        return true;
    }

    public function markAsRead(User $user): bool
    {
        return $this->repository->checkInputIds(
            user: $this->auth,
            inputIds: (array) $this->request->input('message_ids')
        );
    }
}
