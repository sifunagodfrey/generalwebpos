<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_payment_methods.
 * Generated from the supplied Suweka POS migrations.
 */
class PaymentMethod extends Model
{
    protected $table = 'su_payment_methods';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_id',
        'method',
        'amount',
        'reference',
        'staff_id',
        'shift_id',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cashbox\Shift::class, 'shift_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Receipt::class, 'payment_id');
    }

    public function salePaymentAllocations(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SalePaymentAllocation::class, 'payment_method_id');
    }
}
