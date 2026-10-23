<?php
namespace App\Http\Requests\Manage\Customers;
use Illuminate\Foundation\Http\FormRequest;
class CustomerRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
    ]; }
}
