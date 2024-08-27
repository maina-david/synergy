<?php

namespace App\Http\Requests\Billing;

use App\Enums\Billing\Cart\AllowedCartItemTypes;
use App\Models\Administration\Module;
use App\Models\Organization\OrganizationStorageSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddItemToCartRequest extends FormRequest
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
            'item_type' => ['required', 'string', Rule::enum(AllowedCartItemTypes::class)],
            'item_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $itemType = $this->input('item_type');

                    if ($itemType === AllowedCartItemTypes::MODULE->value) {
                        if (!Module::where('id', $value)->exists()) {
                            $fail('The selected module does not exist.');
                        }
                    } elseif ($itemType === AllowedCartItemTypes::STORAGE->value) {
                        if (!OrganizationStorageSpace::where('id', $value)->exists()) {
                            $fail('The selected storage space does not exist.');
                        }
                    }
                },
            ],
            'quantity' => ['required', 'integer'],
        ];
    }
}