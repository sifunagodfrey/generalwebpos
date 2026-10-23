<?php
namespace App\Http\Requests\Manage\Inventory;
use Illuminate\Foundation\Http\FormRequest;
class ProductRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|integer|exists:su_categories,id',
            'brand_id' => 'nullable|integer|exists:su_brands,id',
            'unit_of_measure_id' => 'nullable|integer|exists:su_units_of_measure,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'has_variants' => 'boolean',
            'is_active' => 'boolean',
    ]; }
}
