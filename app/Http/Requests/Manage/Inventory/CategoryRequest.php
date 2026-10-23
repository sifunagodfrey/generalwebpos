<?php
namespace App\Http\Requests\Manage\Inventory;
use Illuminate\Foundation\Http\FormRequest;
class CategoryRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:su_categories,id',
            'image_path' => 'nullable|string',
    ]; }
}
