<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GradeRangeRequest extends FormRequest
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
        if($this->route()->action['as'] === RouteName::GRADE_RANGE_CREATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] === RouteName::GRADE_RANGE_UPDATE) {
            return $this->updateRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function updateRules(): array
    {
        return [
            'grading_rule_id' => 'required|integer|exists:grading_rules,id',
            ...$this->sharedRules(),
        ];
    }

    private function sharedRules(): array
    {
        return [
            'lower' => 'required|integer|min:0',
            'upper' => 'required|integer',
            'grade' => 'required|max:5',
        ];
    }
}
