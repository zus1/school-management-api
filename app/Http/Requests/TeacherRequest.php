<?php

namespace App\Http\Requests;

use App\Http\Requests\Rules\TeacherRules;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TeacherRequest extends UserRequest
{
    public function __construct(
        private TeacherRules $rules,
    ){
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
        return [
            ...$this->baseRules(),
            ...$this->rules->getRules(),
        ];

        throw new HttpException(422, 'Unprocessable entity');
    }
}
