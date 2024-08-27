<?php

namespace App\Http\Requests;

use App\Constant\Calendar\CalendarEventRepeat;
use App\Constant\RouteName;
use App\Constant\UserType;
use App\Repository\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventRequest extends FormRequest
{
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
        if($this->route()->action['as'] === RouteName::EVENT_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::EVENT_UPDATE) {
            return $this->updateRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    protected function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            'notify_user_ids' => 'array',
            'notify_user_ids.*' => function (string $attribute, mixed $value, \Closure $fail) {
                [$userType, $userId] = explode('.', $value);

                if(in_array($userType, UserType::getValues()) === false) {
                    $fail('Invalid user type '.$userType);

                    return;
                }

                if(is_numeric($userId) === false) {
                    $fail('User id must be numeric');
                }

                /** @var UserRepository $repository */
                $repository = App::make(UserType::repositoryClass($userType));
                if($repository->findOneBy(['id' => $userId]) === null) {
                    $fail(sprintf('%s with id %d dose not exist', ucfirst($userType), $userId));
                }
            },
        ];
    }

    protected function updateRules(): array
    {
        return $this->sharedRules();
    }

    private function sharedRules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'content' => 'required|string|max:3000',
            'starts_at' => 'required|date',
            'ends_at' => [
                'required',
                'date',
                function (string $attribute, mixed $value, \Closure $fails) {
                    $startsAt = $this->input('starts_at');
                    if(strtotime($value) <= strtotime($startsAt)) {
                        $fails(sprintf('%s must be greater then %s', $attribute, $startsAt));
                    }
                }
            ],
            'repeatable_status' => Rule::in(CalendarEventRepeat::getValues()),
        ];
    }
}
