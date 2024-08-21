<?php

namespace App\Http\Requests;

use App\Constant\UserType;
use App\Http\Requests\Rules\GuardianRules;
use App\Http\Requests\Rules\TeacherRules;
use Illuminate\Validation\Rule;

class RegisterRequest extends UserRequest
{
    public function __construct(
        private TeacherRules $teacherRules,
        private GuardianRules $guardianRules,
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
        $userType = $this->query('type');
        $rules = [
            ...$this->baseRegisterRules(),
            'type' => [
                'required',
                Rule::in(UserType::getValues()),
            ],
        ] ;

        if($userType === UserType::TEACHER) {
            $rules = [
                ...$rules,
                ...$this->teacherRules->getRules(),
            ];
        }
        if($userType === UserType::GUARDIAN) {
            $rules = [
                ...$rules,
                ...$this->guardianRules(),
            ];
        }

        return $rules;
    }

    private function guardianRules(): array
    {
        return [
            ...$this->guardianRules->getRules(),
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer',
        ];
    }
}
