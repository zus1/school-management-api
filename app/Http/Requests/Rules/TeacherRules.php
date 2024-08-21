<?php

namespace App\Http\Requests\Rules;

class TeacherRules
{
    public function getRules(): array
    {
        return [
            'months_of_employment' => 'required|integer',
            'employed_at' => 'required|date',
            'social_security_number' => 'required|string|max:9|min:9|regex:/[0-9]+/'
        ];
    }
}
