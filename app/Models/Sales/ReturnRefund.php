<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_returns_refunds.
 * Generated from the supplied Suweka POS migrations.
 */
class ReturnRefund extends Model
{
    protected $table = 'su_returns_refunds';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'quantity',
        'reason',
        'amount_refunded',
        'staff_id',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'amount_refunded' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\SaleItem::class, 'sale_item_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function creditNotes(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\CreditNote::class, 'return_id');
    }
}
