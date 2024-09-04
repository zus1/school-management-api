<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use App\Http\Requests\Rules\SchoolDirectoryRules;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AttendanceRequest extends FormRequest
{
    public function __construct(
        private SchoolDirectoryRules $rules,
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
        if($this->route()->action['as'] === RouteName::ATTENDANCE_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::ATTENDANCE_UPDATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] === RouteName::ATTENDANCES_AGGREGATE) {
            return $this->aggregateRules();
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

    private function aggregateRules(): array
    {
        return [
            'student_id' => $this->rules->studentId(isRequired: false),
            'subject_id' => $this->rules->subjectId(isRequired: false),
            'teacher_id' => $this->rules->teacherId(isRequired: false),
            'school_class_id' => $this->rules->schoolClassId(isRequired: false),
        ];
    }

    private function sharedRules(): array
    {
        return [
            'comment' => 'string|max:1000'
        ];
    }
}
