<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if($this->route()->action['as'] === RouteName::CLASSROOM_TOGGLE_EQUIPMENT) {
            return $this->toggleEquipmentRules();
        }
        if($this->route()->action['as'] === RouteName::CLASSROOM_UPDATE_EQUIPMENT_QUANTITY) {
            return $this->updateEquipmentQuantityRules();
        }

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

    private function toggleEquipmentRules(): array
    {
        $this->additionalModifyEquipmentRules();

        return [
            'action' => 'required|in:add,remove',
            'quantity' => [
                'integer',
                Rule::requiredIf(fn () => $this->input('action') === 'add')
            ],
        ];
    }

    private function updateEquipmentQuantityRules(): array
    {
        $this->additionalModifyEquipmentRules();

        return [
            'action' => 'required|in:increase,decrease',
            'quantity' => 'required|integer',
        ];
    }

    private function additionalModifyEquipmentRules(): void
    {
        $action = $this->query('action');
        if($action === 'remove' || $action == 'decrease') {
            return;
        }

        $quantity = $this->input('quantity');
        if($quantity === null) {
            return;
        }
        /** @var Equipment $equipment */
        $equipment = $this->route()->parameter('equipment');

        if($equipment->available_quantity - $quantity < 0) {
            throw new HttpException(422, 'Assigned quantity is greater then available quantity');
        }
    }
}
