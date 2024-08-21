<?php

namespace App\Http\Requests;

use App\Constant\UserType;
use App\Http\Requests\Rules\GuardianRules;
use App\Http\Requests\Rules\StudentRules;
use App\Http\Requests\Rules\TeacherRules;
use Illuminate\Validation\Rule;

class MeRequest extends UserRequest
{
    public function __construct(
        private TeacherRules $teacherRules,
        private GuardianRules $guardianRules,
        private StudentRules $studentRules,
    ) {
        parent::__construct();
    }

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
        $userType = $this->query('user_type');

        $rules = [
            'user_type' => [
                'required',
                Rule::in(UserType::getValues())
            ],
            ...$this->baseRules(),
        ];

        if($userType === UserType::TEACHER) {
            $rules = [
                ...$rules,
                ...$this->teacherRules->getRules(),
            ];
        }
        if($userType === UserType::GUARDIAN) {
            $rules = [
                ...$rules,
                ...$this->guardianRules->getRules(),
            ];
        }
        if($userType === UserType::STUDENT) {
            $rules = [
                ...$rules,
                ...$this->studentRules->getRules(),
            ];
        }

        return $rules;
    }
}
