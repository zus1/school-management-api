<?php

namespace App\Http\Requests;

use App\Http\Requests\Rules\AnswerRules;
use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function __construct(
        private AnswerRules $rules,
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
            'answer' => $this->rules->answerRules(),
            'position' => $this->rules->positionRules(),
        ];
    }
}
