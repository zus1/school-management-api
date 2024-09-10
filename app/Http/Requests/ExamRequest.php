<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamRequest extends FormRequest
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
        if($this->route()->action['as'] === RouteName::EXAM_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_UPDATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_TOGGLE_GRADING_RULE) {
            return $this->toggleRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_TOGGLE_ALLOWED_SCHOOL_CLASS) {
            return $this->toggleRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            'grading_rule_id' => 'integer|exists:grading_rules,id',
        ];
    }

    private function sharedRules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'description' => 'string|max:200|nullable',
            'starts_at' => 'required|date',
            'ends_at' => [
                'required',
                'date',
                function (string $attribute, string $value, \Closure $fails) {
                    $startsAt = $this->input('starts_at');

                    if($value <= $startsAt) {
                        $fails(sprintf('%s must be grater than %s', $attribute, $startsAt));
                    }
                }
            ],
            'subject_id' => 'required|integer|exists:subjects,id',
        ];
    }

    private function toggleRules(): array
    {
        return [
            'action' => 'required|in:add,remove',
        ];
    }
}
