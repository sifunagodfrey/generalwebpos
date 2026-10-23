<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdminVerifyTwoFaRequest extends FormRequest
{
    // ---------------
    // All requests authorized
    // ---------------
    public function authorize(): bool
    {
        return true;
    }

    // ---------------
    // Validation rules
    // ---------------
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ];
    }

    // ---------------
    // Custom messages
    // ---------------
    public function messages(): array
    {
        return [
            'code.required' => 'Please enter the verification code.',
            'code.size'     => 'The verification code must be 6 digits.',
            'code.regex'    => 'The verification code must contain only digits.',
        ];
    }
}
