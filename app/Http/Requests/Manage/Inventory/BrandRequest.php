<?php
namespace App\Http\Requests\Manage\Inventory;
use Illuminate\Foundation\Http\FormRequest;
class BrandRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
    ]; }
}
