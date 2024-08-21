<?php

namespace App\Http\Requests;


use App\Http\Requests\Rules\UserRules;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    private UserRules $rules;

    public function setUserRules(UserRules $userRules): void
    {
        $this->rules = $userRules;
    }

    protected function baseRegisterRules(): array
    {
        return [
            ...$this->contactRules(),
            ...$this->passwordRules(),
            ...$this->baseRules(),
        ];
    }

    protected function contactRules(): array
    {
        return [
            'email' => $this->rules->emailRules(),
            'phone' => $this->rules->phoneRules(),
        ];
    }

    protected function baseRules(): array
    {
        return [
            'first_name' => $this->rules->firstNameRules(),
            'last_name' => $this->rules->lastNameRules(),
            'gender' => $this->rules->genderRules(),
            'dob' => $this->rules->dobRules(),
        ];
    }

    protected function passwordRules(bool $required = true): array
    {
        return [
            'password' => $this->rules->passwordRules($required),
            'confirm_password' => $this->rules->confirmPasswordRules(),
        ];
    }
}
