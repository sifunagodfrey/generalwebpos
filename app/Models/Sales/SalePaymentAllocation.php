<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_sale_payment_allocations.
 * Generated from the supplied Suweka POS migrations.
 */
class SalePaymentAllocation extends Model
{
    protected $table = 'su_sale_payment_allocations';
    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'payment_method_id',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\PaymentMethod::class, 'payment_method_id');
    }
}
