<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mess_code' => ['required', 'string', Rule::exists('messes', 'code')],
        ];
    }

    public function messages(): array
    {
        return [
            'mess_code.exists' => 'The mess code is invalid. Please check and try again.',
        ];
    }
}
