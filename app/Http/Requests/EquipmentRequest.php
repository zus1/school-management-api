<?php

namespace App\Http\Requests;

use App\Constant\RouteName;
use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EquipmentRequest extends FormRequest
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
        if($this->route()->action['as'] === RouteName::EQUIPMENT_CREATE) {
            return $this->sharedRules();
        }
        if($this->route()->action['as'] === RouteName::EQUIPMENT_UPDATE) {
            return $this->updateRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function updateRules(): array
    {
        $this->additionalUpdateRules();

        return $this->sharedRules();
    }

    private function additionalUpdateRules(): void
    {
        /** @var Equipment $equipment */
        $equipment = $this->route()->parameter('equipment');
        $totalQuantity = $this->input('total_quantity');

        if($totalQuantity >= $equipment->total_quantity) {
            return;
        }

        $decrease = $equipment->total_quantity - $totalQuantity;

        if($equipment->available_quantity - $decrease < 0) {
            throw new HttpException(
                422,
                'Not allowed, please unassign some equipment. Otherwise available quantity will be less then 0 after this action'
            );
        }
    }

    private function sharedRules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:2000',
            'total_quantity' => 'required|integer|min:0',
            'type' => 'required|string',
            'cost_per_unit' => 'required|decimal:0,2|min:1',
            'weight' => 'required|integer',
            'height' => 'required|integer',
            'width' => 'required|integer',
            'length' => 'required|integer'
        ];
    }
}
