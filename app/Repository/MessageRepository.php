<?php

namespace App\Repository;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class MessageRepository extends LaravelBaseRepository
{
    protected const MODEL = Message::class;

    public function __construct(
        private UserRepository $userRepository,
    ){
    }

    public function create(array $data, User $recipient, bool $system = false): Message
    {
        $message = new Message();
        $this->modifySharedFields($message, $data);
        $message->is_read = false;

        if($system === false) {
            $this->associateSender($message);
        }

        $message->recipient()->associate($recipient);

        $message->save();

        return $message;
    }

    private function associateSender(Message $message): void
    {
        $sender = $this->userRepository->findAuthParent();
        $message->sender()->associate($sender);
    }

    public function update(array $data, Message $message): Message
    {
        $this->modifySharedFields($message, $data);

        $message->save();

        return $message;
    }

    public function bulkMarkAsRead(array $messageIds): Collection
    {
        $builder = $this->getBuilder();
        $messages = $builder->whereIn('id', $messageIds)->get();

        $marked = [];
        foreach ($messages as $message) {
            $this->markAsRead($message, save: false);

            $marked[] = $message;
        }

        Message::massUpdate($marked);

        return new Collection($marked);
    }

    public function markAsRead(Message $message, bool $save = true): Message
    {
        if($message->is_read === true) {
            return $message;
        }

        $message->is_read = true;
        $message->read_at = Carbon::now()->format('Y-m-d H:i:s');

        if($save === true) {
            $message->save();
        }

        return $message;
    }

    public function checkInputIds(User $user, array $inputIds): bool
    {
        $builder = $this->getBuilder();

        $builder->whereRelation('recipient', 'id', $user->id)
            ->where('id', $inputIds[0]);

        unset($inputIds[0]);

        foreach ($inputIds as $id) {
            $builder->union($this->getBuilder()->whereRelation('recipient', 'id', $user->id)->where('id', $id));
        }

        return $builder->count() === count($inputIds) + 1;
    }

    private function modifySharedFields(Message $message, array $data): void
    {
        $message->title = $data['title'];
        $message->content = $data['content'];
    }
}
