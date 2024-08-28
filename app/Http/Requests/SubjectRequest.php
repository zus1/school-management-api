<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use App\Repository\SchoolClassRepository;
use App\Repository\TeacherRepository;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubjectRequest extends FormRequest
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private SchoolClassRepository $schoolClassRepository,
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
        if($this->route()->action['as'] === RouteName::SUBJECT_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::SUBJECT_UPDATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] == RouteName::SUBJECT_TOGGLE_LECTURER) {
            return $this->toggleRules();
        }
        if($this->route()->action['as'] === RouteName::SUBJECT_TOGGLE_LECTURER_CLASSES) {
            return $this->toggleRules(true);
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            ...$this->sharedRules(),
            'lecturer_ids' => 'array',
            'lecturer_ids.*' => function (string $attribute, array $lecturerIdClassIds, \Closure $fails) {
                $lecturerId = array_key_first($lecturerIdClassIds);
                $schoolClassIds = $lecturerIdClassIds[$lecturerId];

                if(!is_int($lecturerId)) {
                    $fails('Lecturer id must be integer');
                }

                if(array_filter($schoolClassIds, 'is_int') !== $schoolClassIds) {
                    $fails('All school ids must be integers');
                }

                if($this->teacherRepository->findOneBy(['id' => $lecturerId]) === null) {
                    $fails('Lecturer id must exist in teachers table');
                }
                if($this->schoolClassRepository->existsManyById($schoolClassIds) === false) {
                    $fails('School class ids must exist in school_classes table');
                }
            }
        ];
    }

    private function toggleRules(bool $schoolClassesRequired = false): array
    {
        $rules = [
            'action' => 'required|in:add,remove',
            'school_class_ids' => [
                'array',
            ],
            'school_class_ids.*' => 'integer|exists:school_classes,id'
        ];

        if($schoolClassesRequired === true) {
            $rules['school_class_ids'][] = 'required';
        }

        return $rules;
    }

    private function sharedRules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'is_elective' => 'boolean',
            'school_year_id' => 'required|integer|exists:school_years,id',
        ];
    }
}
