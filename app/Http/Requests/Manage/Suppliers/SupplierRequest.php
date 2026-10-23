<?php
namespace App\Http\Requests\Manage\Suppliers;
use Illuminate\Foundation\Http\FormRequest;
class SupplierRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'other_details' => 'nullable|string',
    ]; }
}
