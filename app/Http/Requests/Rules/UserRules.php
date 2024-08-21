<?php

namespace App\Http\Requests\Rules;

use App\Constant\Gender;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserRules
{
    public function get(string $field): string|array
    {
        $getter = sprintf('%sRules', Str::camel($field));

        if(!method_exists($this, $getter)) {
            throw new HttpException(500, 'Rule not found');
        }

        return $this->$getter();
    }

    public function emailRules(): string
    {
        return 'required|email|unique:users';
    }

    public function passwordRules(bool $required = true): array
    {
        $rules =  [
            Password::min(8)
                ->letters()
                ->numbers()
                ->symbols()
                ->mixedCase()
                ->uncompromised(),
        ] ;

        if($required === true) {
            $rules[] = 'required';
        }

        return $rules;
    }

    public function confirmPasswordRules(): string
    {
        return 'required|same:password';
    }

    public function firstNameRules(): string
    {
        return 'required|string|max:50';
    }

    public function lastNameRules(): string
    {
        return 'required|string|max:60';
    }

    public function dobRules(): string
    {
        return 'required|date';
    }

    public function genderRules(): array
    {
        return [
            'required',
            Rule::in(Gender::getValues()),
        ];
    }

    public function phoneRules(): string
    {
        return 'required|regex:/^\\+?[1-9][0-9]{7,14}$/';
    }
}
