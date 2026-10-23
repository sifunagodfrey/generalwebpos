<?php
namespace App\Http\Requests\Manage\Sales;
use Illuminate\Foundation\Http\FormRequest;
class CheckoutRequest extends FormRequest {
 public function authorize(): bool { return auth()->check(); }
 public function rules(): array { return [
 'action'=>['required','in:hold,complete'],
 'lines'=>['required','array','min:1','max:100'],
 'lines.*.product_id'=>['required','integer','exists:su_products,id'],
 'lines.*.variant_id'=>['nullable','integer','exists:su_product_variants,id'],
 'lines.*.quantity'=>['required','numeric','gt:0','lte:100000'],
 'lines.*.manual_discount'=>['nullable','numeric','min:0'],
 'lines.*.manual_discount_percent'=>['nullable','numeric','between:0,100'],
 'customer_id'=>['nullable','integer','exists:su_customers,id'],
 'held_sale_id'=>['nullable','integer','exists:su_sales,id'],
 'hold_label'=>['nullable','string','max:255'],
 'payment_method'=>['nullable','in:cash,card,mpesa'],
 'amount_tendered'=>['nullable','numeric','min:0'],
 'payment_reference'=>['nullable','string','max:255'],
 'over_limit_approved'=>['nullable','boolean'],
 ]; }
}
