<?php
namespace App\Http\Requests\Manage\Cashbox;
use Illuminate\Foundation\Http\FormRequest;
class CloseShiftRequest extends FormRequest {
 public function authorize(): bool {return $this->user() !== null;}
 public function rules(): array {return ['closing_cash_counted'=>'required|numeric|min:0','closing_mobile_money_counted'=>'required|numeric|min:0'];}
}
