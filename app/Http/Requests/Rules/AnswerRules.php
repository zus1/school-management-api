<?php

namespace App\Http\Requests\Rules;

class AnswerRules
{
    public function answerRules(): string
    {
        return 'required|string|max:100';
    }

    public function positionRules(): string
    {
        return 'int|nullable';
    }
}
