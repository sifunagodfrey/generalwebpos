<?php

namespace App\Http\Requests\Manage\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mail_driver'   => 'required|in:smtp,sendmail,mailgun',
            'mail_from'     => 'required|email|max:255',
            'mail_name'     => 'required|string|max:255',
            'smtp_host'     => 'nullable|string|max:255',
            'smtp_port'     => 'nullable|integer|min:1|max:65535',
            'smtp_user'     => 'nullable|string|max:255',
            'smtp_pass'     => 'nullable|string|max:255',
            'smtp_encrypt'  => 'required|in:tls,ssl,none',
        ];
    }
}
