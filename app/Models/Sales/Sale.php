<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_sales.
 * Generated from the supplied Suweka POS migrations.
 */
class Sale extends Model
{
    protected $table = 'su_sales';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'staff_id',
        'shift_id',
        'customer_id',
        'status',
        'hold_label',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'held_at',
        'completed_at',
        'void_reason',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'created_at' => 'datetime',
            'held_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cashbox\Shift::class, 'shift_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class, 'customer_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'sale_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\PaymentMethod::class, 'sale_id');
    }

    public function returnsRefunds(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\ReturnRefund::class, 'sale_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CreditTransaction::class, 'sale_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'converted_to_sale_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Receipt::class, 'sale_id');
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DeliveryNote::class, 'sale_id');
    }

    public function mpesaApplications(): HasMany
    {
        return $this->hasMany(\App\Models\Payments\MpesaApplication::class, 'sale_id');
    }

    public function salePaymentAllocations(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SalePaymentAllocation::class, 'sale_id');
    }
}
