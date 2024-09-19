<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules(): array {
        return \App\Models\Users::getValidationRules();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array {
        return [
            'position_id' => 'position',
            'phone' => 'mobile phone',
            'photo' => 'user photo',
        ];
    }

    public function messages()
    {
        return[
            'photo.max'     => 'The photo size must not be greater than 5 Mb',
            'page.integer'  => 'The count must be an integer.',
            'count.integer' => 'The count must be an integer.'
        ];
    }

}
