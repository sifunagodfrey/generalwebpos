<?php
namespace App\Http\Requests\Manage\Cashbox;
use Illuminate\Foundation\Http\FormRequest;
class OpenShiftRequest extends FormRequest {
 public function authorize(): bool { return $this->user() !== null; }
 public function rules(): array {return ['opening_cash'=>'required|numeric|min:0','opening_mobile_money'=>'required|numeric|min:0'];}
}
