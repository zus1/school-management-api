<?php

namespace App\Http\Requests;

use App\Constant\Roles;
use App\Http\Requests\Rules\GuardianRules;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;

class GuardianRequest extends UserRequest
{
    public function __construct(
        private GuardianRules $rules,
    ){
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
        /** @var User $auth */
        $auth = Auth::user();

        $rules = $this->rules->getRules();

        if($auth->hasRole(Roles::ADMIN)) {
            $rules = [
                ...$rules,
                ...$this->baseRules(),
            ];
        }

        return $rules;
    }
}
