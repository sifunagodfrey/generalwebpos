<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_credit_transactions.
 * Generated from the supplied Suweka POS migrations.
 */
class CreditTransaction extends Model
{
    protected $table = 'su_credit_transactions';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'customer_id',
        'sale_id',
        'type',
        'amount',
        'note',
        'staff_id',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class, 'customer_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
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
