<?php

namespace App\Http\Requests;

use App\Constant\Media\MediaOwner;
use App\Constant\Media\MediaType;
use App\Rules\MediaValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaRequest extends FormRequest
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
            'owner_type' => [
                'required',
                Rule::in(MediaOwner::getValues()),
            ],
            'media_type' => [
                'required',
                Rule::in(MediaType::getValues()),
            ],
            'media' => [
                'required',
                new MediaValidationRule(),
            ],
        ];
    }
}
