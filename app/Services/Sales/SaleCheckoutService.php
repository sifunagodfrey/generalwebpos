<?php
namespace App\Services\Sales;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Server-authoritative sale pricing. Prices and stock never come from the browser. */
class SaleCheckoutService
{
    private function fail(string $message): never { throw ValidationException::withMessages(['sale' => $message]); }

    public function staffId(int $userId): ?int
    {
        $existing = DB::table('su_staff')->where('user_id', $userId)->first();
        if ($existing) return (int) $existing->is_active === 1 ? (int) $existing->id : null;
        $user = \App\Models\User::find($userId);
        if (!$user || $user->status !== 'active' || !$user->hasAnyPermission([
            'pos.sales.create', 'pos.sales.manage', 'pos.sales.hold',
        ])) return null;
        // Serialized by the user row to avoid duplicate staff links on simultaneous requests.
        return DB::transaction(function () use ($userId, $user) {
            DB::table('su_users')->where('id', $userId)->lockForUpdate()->first();
            $staff = DB::table('su_staff')->where('user_id', $userId)->first();
            if ($staff) return (int) $staff->is_active === 1 ? (int) $staff->id : null;
            return (int) DB::table('su_staff')->insertGetId([
                'user_id' => $userId,
                'first_name' => $user->first_name ?: 'POS',
                'last_name' => $user->last_name ?: 'User',
                'role' => 'cashier',
                'pin_hash' => \Illuminate\Support\Facades\Hash::make(bin2hex(random_bytes(24))),
                'is_active' => 1,
                'created_at' => now(),
            ]);
        });
    }

    public function vat(): array
    {
        $settings = DB::table('su_settings')->whereIn('setting_key', ['vat_enabled','vat_rate','vat_inclusive'])->pluck('setting_value','setting_key');
        $enabled = in_array(strtolower((string)($settings['vat_enabled'] ?? 'false')), ['true','1','yes'], true);
        $rate = (float)($settings['vat_rate'] ?? 0);
        if ($rate < 0 || $rate > 100) $this->fail('VAT configuration is invalid.');
        return ['enabled'=>$enabled,'rate'=>$rate,'inclusive'=>in_array(strtolower((string)($settings['vat_inclusive'] ?? 'false')), ['true','1','yes'], true)];
    }

    private function pricedLines(array $rows, ?int $customerId, bool $lock): array
    {
        $result = []; $subtotal=0; $discountTotal=0; $taxTotal=0;
        $vat=$this->vat(); $used=[];
        foreach ($rows as $row) {
            $product=DB::table('su_products')->where('id',$row['product_id'])->where('is_active',1)->first();
            if (!$product) $this->fail('Product is unavailable.');
            $variantId=$row['variant_id'] ?? null; $variant=null;
            if ($variantId !== null) {
                $variant=DB::table('su_product_variants')->where('id',$variantId)->where('product_id',$product->id)->first();
                if (!$variant) $this->fail('Invalid variant for '.$product->name);
            } elseif ($product->has_variants) $this->fail('Select a variant of '.$product->name);
            $key=$product->id.':'.($variantId ?? 'base');
            if (isset($used[$key])) $this->fail('Duplicate cart line for '.$product->name);
            $used[$key]=true;
            $qty=(float)$row['quantity'];
            if (!is_finite($qty) || $qty<=0) $this->fail('Invalid quantity.');
            $base=(float)($variant->selling_price ?? $product->selling_price);
            if ($base<0) $this->fail('Product price cannot be negative.');
            $price=$base;
            $inventoryPrice=$variant->discount_price ?? $product->discount_price;
            if ($inventoryPrice!==null && (float)$inventoryPrice>=0) $price=min($price,(float)$inventoryPrice);
            $offers=DB::table('su_discounts_promotions')->where('starts_at','<=',now())->where('ends_at','>=',now())
                ->where(function($q)use($product,$variantId){$q->where(function($x)use($variantId){$x->where('variant_id',$variantId ?? 0);})->orWhere(function($x)use($product){$x->whereNull('variant_id')->where('product_id',$product->id);});})->get();
            foreach ($offers as $offer) {
                if ($offer->discount_percent!==null && (float)$offer->discount_percent>=0 && (float)$offer->discount_percent<=100) $price=min($price,round($base*(1-(float)$offer->discount_percent/100),2));
                elseif ($offer->discount_amount!==null && (float)$offer->discount_amount>=0) $price=min($price,max(0,$base-(float)$offer->discount_amount));
            }
            $wholesale=DB::table('su_wholesale_pricing')->where(function($q)use($product,$variantId){$variantId ? $q->where('variant_id',$variantId) : $q->whereNull('variant_id')->where('product_id',$product->id);})
                ->where(function($q)use($customerId){$q->whereNull('customer_id');if($customerId)$q->orWhere('customer_id',$customerId);})
                ->where(function($q)use($qty){$q->whereNull('min_quantity')->orWhere('min_quantity','<=',$qty);})
                ->min('price');
            if ($wholesale!==null && (float)$wholesale>=0) $price=min($price,(float)$wholesale);
            $manual=(float)($row['manual_discount'] ?? 0);
            $percent=(float)($row['manual_discount_percent'] ?? 0);
            if ($manual<0 || $percent<0 || $percent>100 || ($manual>0 && $percent>0)) $this->fail('Invalid line discount.');
            $perUnitDiscount=$percent>0 ? round($price*$percent/100,2) : $manual;
            if ($perUnitDiscount>$price) $this->fail('Discount exceeds unit price.');
            // sale_items stores list price and combined discount so historical receipts retain the actual price paid.
            $gross=round($base*$qty,2);
            $net=round(($price-$perUnitDiscount)*$qty,2);
            $discount=round($gross-$net,2);
            $tax=0;
            if ($vat['enabled'] && $product->tax_type==='standard_rated') {
                $tax=round($vat['inclusive'] ? $net-$net/(1+$vat['rate']/100) : $net*$vat['rate']/100,2);
            }
            $batches=DB::table('su_stock_batches')->where('product_id',$product->id)
                ->when($variantId!==null,fn($q)=>$q->where('variant_id',$variantId),fn($q)=>$q->whereNull('variant_id'))
                ->where('quantity','>',0)->where(function($q){$q->whereNull('expiry_date')->orWhereDate('expiry_date','>=',today());})
                ->orderByRaw('expiry_date IS NULL, expiry_date ASC')->orderBy('id');
            if ($lock) $batches->lockForUpdate();
            $batches=$batches->get();
            $available=round((float)$batches->sum('quantity'),4);
            if ($lock && $available+0.00001<$qty) $this->fail($product->name.' has only '.$available.' available.');
            $result[]=['product_id'=>$product->id,'variant_id'=>$variantId,'name'=>$product->name.($variant?' - '.$variant->name:''),
                'quantity'=>$qty,'unit_price'=>$base,'discount'=>$discount,'tax_amount'=>$tax,
                'line_total'=>round($net+($vat['inclusive']?0:$tax),2),'batches'=>$batches,'available'=>$available];
            $subtotal+=$gross; $discountTotal+=$discount; $taxTotal+=$tax;
        }
        return ['lines'=>$result,'subtotal'=>round($subtotal,2),'discount_total'=>round($discountTotal,2),'tax_total'=>round($taxTotal,2),
            'total'=>round($subtotal-$discountTotal+($vat['inclusive']?0:$taxTotal),2),'vat'=>$vat];
    }

    public function quote(array $data): array { return $this->pricedLines($data['lines'], $data['customer_id'] ?? null, false); }

    public function save(array $data,int $userId,bool $complete): int
    {
        return DB::transaction(function()use($data,$userId,$complete){
            $staffId=$this->staffId($userId);
            if (!$staffId) $this->fail('Your login must be linked to an active staff record.');
            $shift=DB::table('su_shifts')->where('staff_id',$staffId)->where('is_closed',0)->orderByDesc('id')->lockForUpdate()->first();
            // ---------------
            // A sale, held or completed, needs an open shift
            // ---------------
            if (!$shift) $this->fail('Open a shift before making a sale.');
            $customerId=$data['customer_id'] ?? null;
            $quote=$this->pricedLines($data['lines'],$customerId,$complete);
            $heldId=$data['held_sale_id'] ?? null;
            if ($heldId) {
                $held=DB::table('su_sales')->where('id',$heldId)->lockForUpdate()->first();
                if (!$held || $held->status!=='held' || (int)$held->staff_id!==$staffId) $this->fail('Held sale is unavailable.');
            }
            $payment = null;
            $creditAmount = 0.0;
            $customer = null;
            if ($complete) {
                $method = (string) ($data['payment_method'] ?? '');
                if (!in_array($method, ['cash', 'card', 'mpesa'], true)) $this->fail('Choose a payment method.');
                $rendered = round((float) ($data['amount_tendered'] ?? 0), 2);
                $total = (float) $quote['total'];
                $applied = min($total, $rendered);
                $creditAmount = round(max(0, $total - $applied), 2);
                $reference = trim((string) ($data['payment_reference'] ?? ''));
                if ($method === 'card' && $applied > 0 && $reference === '') {
                    $this->fail('Enter the card terminal transaction reference.');
                }
                if ($method === 'mpesa') {
                    // No verified M-Pesa receipt-reservation integration is installed in Laravel yet.
                    // A reference typed by a cashier must NEVER mark a mobile-money payment as received.
                    $this->fail('M-Pesa verification is not connected. Select Cash or Card.');
                }
                if ($creditAmount > 0) {
                    if (!$customerId) $this->fail('Choose a customer for the outstanding credit.');
                    $customer = DB::table('su_customers')->where('id', $customerId)->lockForUpdate()->first();
                    if (!$customer) $this->fail('Customer is unavailable.');
                    $limit = $customer->credit_limit === null ? null : (float) $customer->credit_limit;
                    if ($limit !== null && ((float) $customer->credit_balance + $creditAmount) > $limit + 0.001) {
                        $user = \App\Models\User::find($userId);
                        if (empty($data['over_limit_approved']) || !$user || !$user->hasAnyPermission(['pos.customers.manage', 'pos.sales.manage'])) {
                            $this->fail('Credit limit exceeded. A manager must approve the extra credit.');
                        }
                    }
                }
                $payment = ['method' => $method, 'amount' => $applied, 'reference' => $reference !== '' ? $reference : null];
            }
            $values=['staff_id'=>$staffId,'shift_id'=>$shift->id ?? null,'customer_id'=>$customerId,
                'status'=>$complete?'completed':'held','hold_label'=>$complete?null:($data['hold_label'] ?? null),
                'subtotal'=>$quote['subtotal'],'discount_total'=>$quote['discount_total'],'tax_total'=>$quote['tax_total'],'total'=>$quote['total'],
                'held_at'=>$complete?null:now(),'completed_at'=>$complete?now():null];
            if ($heldId) {
                DB::table('su_sales')->where('id',$heldId)->update($values);
                DB::table('su_sale_items')->where('sale_id',$heldId)->delete();
                $saleId=(int)$heldId;
            } else $saleId=(int)DB::table('su_sales')->insertGetId($values+['created_at'=>now()]);
            foreach ($quote['lines'] as $line) {
                DB::table('su_sale_items')->insert(['sale_id'=>$saleId,'product_id'=>$line['product_id'], 'variant_id'=>$line['variant_id'],
                    'batch_id'=>null,'quantity'=>$line['quantity'],'unit_price'=>$line['unit_price'], 'discount'=>$line['discount'],
                    'tax_amount'=>$line['tax_amount'],'line_total'=>$line['line_total'],'created_at'=>now(),'updated_at'=>now()]);
                if ($complete) {
                    $remaining=$line['quantity'];
                    foreach ($line['batches'] as $batch) {
                        if ($remaining<=0) break;
                        $take=min($remaining,(float)$batch->quantity);
                        DB::table('su_stock_batches')->where('id',$batch->id)->update(['quantity'=>round((float)$batch->quantity-$take,4),'updated_at'=>now()]);
                        $remaining=round($remaining-$take,4);
                    }
                    if ($remaining>0.00001) $this->fail('Unable to allocate stock.');
                }
            }
            if ($complete) {
                if ($payment['amount'] > 0) {
                    $paymentId = DB::table('su_payment_methods')->insertGetId([
                        'sale_id'=>$saleId, 'method'=>$payment['method'], 'amount'=>$payment['amount'],
                        'reference'=>$payment['reference'], 'staff_id'=>$staffId, 'shift_id'=>$shift->id,
                        'created_at'=>now(),
                    ]);
                    DB::table('su_sale_payment_allocations')->insert([
                        'sale_id'=>$saleId, 'payment_method_id'=>$paymentId,
                        'amount'=>$payment['amount'], 'created_at'=>now(), 'updated_at'=>now(),
                    ]);
                }
                if ($creditAmount > 0) {
                    DB::table('su_customers')->where('id',$customerId)->update([
                        'credit_balance'=>round((float)$customer->credit_balance + $creditAmount,2),
                    ]);
                    DB::table('su_credit_transactions')->insert([
                        'customer_id'=>$customerId, 'sale_id'=>$saleId, 'type'=>'sale',
                        'amount'=>$creditAmount, 'note'=>'Unpaid balance of sale #'.$saleId,
                        'staff_id'=>$staffId, 'created_at'=>now(),
                    ]);
                }
            }
            return $saleId;
        },5);
    }

    public function discard(int $saleId,int $userId): void
    {
        DB::transaction(function()use($saleId,$userId){
            $staffId=$this->staffId($userId);
            $held=DB::table('su_sales')->where('id',$saleId)->lockForUpdate()->first();
            if (!$held || $held->status!=='held' || (int)$held->staff_id!==$staffId) $this->fail('Cannot discard this held sale.');
            DB::table('su_sale_items')->where('sale_id',$saleId)->delete();
            DB::table('su_sales')->where('id',$saleId)->delete();
        });
    }
}
