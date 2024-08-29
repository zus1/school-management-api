<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
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
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:2000',
            'max_capacity' => 'required|integer',
            'floor' => 'required|integer|max:4',
            'number' => 'required|string|max:5',
            'number_of_seats' => 'required|integer',
            'purpose' => 'required|string|max:100',
            'size' => 'array:length,height,width',
            'size.*' => 'required|int',
        ];
    }
}
