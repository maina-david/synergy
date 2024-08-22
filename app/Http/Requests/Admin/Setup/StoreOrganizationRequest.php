<?php

namespace App\Http\Requests\Admin\Setup;

use App\Enums\Admin\Organization\OrganizationCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreOrganizationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'max:255'],
            'email' => ['required', 'email', 'unique:organizations,email'],
            'phone' => ['required', 'unique:organizations,phone'],
            'website' => ['required', 'url', 'unique:organizations,website'],
            'category' => ['string', Rule::enum(OrganizationCategory::class)],
            'logo' => [
                'required',
                File::image()
                    ->min('1kb')
                    ->max('5mb')
            ],
        ];
    }
}