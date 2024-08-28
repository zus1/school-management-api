<?php

namespace App\Http\Requests;

class ExamEventRequest extends EventRequest
{
    protected function createRules(): array
    {
        return [
            ...parent::createRules(),
            ...$this->sharedRules(),
        ];
    }

    protected function updateRules() : array
    {
        return [
            ...parent::updateRules(),
            ...$this->sharedRules(),
        ];
    }

    private function sharedRules(): array
    {
        return [
            'school_class_id' => 'required|integer|exists:school_classes,id',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'subject_id' => 'required|integer|exists:subjects,id'
        ];
    }
}
