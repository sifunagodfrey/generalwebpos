<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_receipts.
 * Generated from the supplied Suweka POS migrations.
 */
class Receipt extends Model
{
    protected $table = 'su_receipts';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_id',
        'payment_id',
        'amount',
        'running_balance',
        'clears_balance',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'running_balance' => 'decimal:2',
            'clears_balance' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\PaymentMethod::class, 'payment_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }
}
