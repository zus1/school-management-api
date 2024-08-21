<?php

namespace App\Http\Requests\Rules;

class GuardianRules
{
    public function getRules(): array
    {
        return [
            'street' => 'required|string|max:100',
            'street_number' => 'required|string|max:10',
            'occupation' => 'required|string|max:50',
            'city' => 'required|string|max:70',
        ];
    }
}
