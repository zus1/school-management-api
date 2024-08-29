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
        $this->additionalCreateRules();

        return [
            ...$this->sharedRules(),
            'lecturer_ids' => 'array',
        ];
    }

    private function additionalCreateRules(): void
    {
        if(($lecturerIds = $this->input('request_ids')) === null) {
            return;
        }

        $lecturerId = array_key_first($lecturerIds);
        $schoolClasses = $lecturerIds[$lecturerId];

        if(!is_int($lecturerId)) {
            throw new HttpException(422, 'Lecturer id must be integer');
        }
        if(array_filter($schoolClasses, 'is_int') !== $schoolClasses) {
            throw new HttpException(422, 'School class id must be integer');
        }

        if($this->teacherRepository->findOneBy(['id' => $lecturerId]) === null) {
            throw new HttpException('Lecturer id must be valid id from lecturers table');
        }
        if($this->schoolClassRepository->existsManyById($schoolClasses) === false) {
            throw new HttpException(422, 'School class id must be valid id from school_classes table');
        }
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
