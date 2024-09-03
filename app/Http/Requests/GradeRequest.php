<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GradeRequest extends FormRequest
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
        if($this->route()->action['as'] === RouteName::GRADE_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::GRADE_UPDATE) {
            return $this->sharedRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            'student_id' => 'required|integer|exists:students,id',
            'subject_id' => 'required|integer|exists:subjects,id',
        ];
    }

    private function sharedRules(): array
    {
        return [
            'grade' => 'required|integer|max:5|min:1',
            'comment' => 'string|max:1000',
        ];
    }
}
