<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentRequest extends UserRequest
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
        if($this->route()->action['as'] === RouteName::STUDENT_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::STUDENT_ONBOARDING) {
            return $this->onboardingRules();
        }
        if($this->route()->action['as'] === RouteName::STUDENT_UPDATE) {
            return $this->baseRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^\\+?[1-9][0-9]{7,14}$/',
        ];
    }

    private function onboardingRules(): array
    {
        return [
            ...$this->baseRules(),
            ...$this->passwordRules(),
        ];
    }
}
