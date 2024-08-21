<?php

namespace App\Http\Requests\Rules;

class StudentRules
{
    public function getRules(): array
    {
        return [
            'class' => 'required|string|max:3',
            'grade' => 'required|int|max:4',
            'major_class' => 'required|string|max:50',
        ];
    }
}
