<?php
namespace App\Http\Requests\Manage\Inventory;
use Illuminate\Foundation\Http\FormRequest;
class UnitOfMeasureRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array { return [
            'name' => 'required|string|max:20',
            'allows_decimal' => 'boolean',
    ]; }
}
