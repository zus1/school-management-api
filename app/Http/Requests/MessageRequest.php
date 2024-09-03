<?php

namespace App\Http\Requests;

use App\Constant\MessageUserType;
use App\Constant\RouteName;
use App\Constant\UserType;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageRequest extends FormRequest
{
    private const UPDATE_WINDOW = 30; //min

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->route()->action['as'] === RouteName::MESSAGE_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::MESSAGE_UPDATE) {
            return $this->updateRules();
        }
        if($this->route()->action['as'] === RouteName::MESSAGES) {
            return $this->collectionRules();
        }
        if($this->route()->action['as'] === RouteName::MESSAGES_MARK_AS_READ) {
            return $this->markAsReadRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            MessageUserType::withSuffix(MessageUserType::RECIPIENT) => Rule::in(UserType::getValues()),
        ];
    }

    private function updateRules(): array
    {
        $this->additionalUpdateRules();

        return $this->sharedRules();
    }

    private function additionalUpdateRules(): void
    {
        /** @var Message $message */
        $message = $this->route()->parameter('message');

        if((new Carbon($message->created_at))
                ->addMinutes(self::UPDATE_WINDOW) < Carbon::now()->format('Y-m-d H:i:S')) {
            throw new HttpException(
                422,
                sprintf('Message can be updated only %d minutes after its created', self::UPDATE_WINDOW)
            );
        }
    }

    private function sharedRules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'content' => 'required|string|max:3000',
        ];
    }

    private function collectionRules(): array
    {
        return [
            'requester_type' => [
                'required',
                Rule::in(MessageUserType::getValues()),
            ],
        ];
    }

    private function markAsReadRules(): array
    {
        return [
            'message_ids' => 'array|min:1',
            'message_ids.*' => 'int|exists:messages,id'
        ];
    }
}
