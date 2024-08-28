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
        return [
            'name'        => 'required|min:2|max:60',
            'email'       => 'required|email:rfc|unique:users,email',
            'phone'       => 'required|starts_with:+380|regex:/^[\+]{0,1}380([0-9]{9})$/|unique:users,phone',
            'position_id' => 'required',
            'photo'       => 'required|image|max:5000',
            'page'        => 'integer|min:1',
            'count'       => 'integer|min:1'            
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array {
        return [
            'position_id' => 'position',
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
