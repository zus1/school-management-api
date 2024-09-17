<?php

namespace App\Http\Requests;

use App\Constant\Analytics\DatePeriod;
use App\Constant\Analytics\DateUnit;
use App\Constant\RouteName;
use App\Http\Requests\Rules\SchoolDirectoryRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GradeRequest extends FormRequest
{
    public function __construct(
        private SchoolDirectoryRules $rules,
    )
    {
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
        if($this->route()->action['as'] === RouteName::GRADE_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::GRADE_UPDATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] === RouteName::ANALYTICS_GRADES_CHART) {
            return $this->analyticsRules();
        }
        if($this->route()->action['as'] === RouteName::GRADES_TOP_AVERAGE) {
            return $this->analyticsRules(isStudentRequired: true);
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            'student_id' => $this->rules->studentId(),
            'subject_id' => $this->rules->subjectId(),
        ];
    }

    private function sharedRules(): array
    {
        return [
            'grade' => 'required|integer|max:5|min:1',
            'comment' => 'string|max:1000',
        ];
    }

    private function analyticsRules(bool $isStudentRequired = false): array
    {
        $rules = [
            'student_id' => 'int|exists:students,id',
            'teacher_id' => 'int|exists:teachers,id',
            'subject_id' => 'int|exists:subjects,id',
            'school_class_id' => 'int|exists:school_classes,id',
            'from' => 'string|date',
            'to' => 'string|date',
            'unit' => [
                'string',
                Rule::in(DateUnit::getValues()),
            ],
            'period' => [
                'string',
                Rule::in(DatePeriod::getValues()),
            ],
        ];

        if($this->input('period') === null) {
            $rules['from'] .= '|required';
            $rules['to'] .= '|required';
            $rules['unit'] .= '|required';
        }

        if($isStudentRequired === true) {
            $rules['student_id'] .= '|required';
        }

        return $rules;
    }
}
