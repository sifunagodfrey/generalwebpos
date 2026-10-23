<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
