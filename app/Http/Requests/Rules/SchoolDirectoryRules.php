<?php

namespace App\Http\Requests\Rules;

class SchoolDirectoryRules
{
    public function teacherId(bool $isRequired = true): string
    {
        $rules = 'integer|exists:teachers,id';

        return $this->addRequiredIfApplicable($isRequired, $rules);
    }

    public function studentId(bool $isRequired = true): string
    {
        $rules = 'integer|exists:students,id';

        return $this->addRequiredIfApplicable($isRequired, $rules);
    }

    public function schoolClassId(bool $isRequired = true): string
    {
        $rules = 'integer|exists:school_classes,id';

        return $this->addRequiredIfApplicable($isRequired, $rules);
    }

    public function subjectId(bool $isRequired = true): string
    {
        $rules = 'integer|exists:subjects,id';

        return $this->addRequiredIfApplicable($isRequired, $rules);
    }

    private function addRequiredIfApplicable(bool $isRequired, string $rules): string
    {
        if($isRequired === true) {
            $rules.= '|required';
        }

        return $rules;
    }
}
