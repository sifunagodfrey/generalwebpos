<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_customers.
 * Generated from the supplied Suweka POS migrations.
 */
class Customer extends Model
{
    protected $table = 'su_customers';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'credit_balance',
        'credit_limit',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'credit_balance' => 'decimal:2',
            'credit_limit' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Sale::class, 'customer_id');
    }

    public function missingStockRequests(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\MissingStockRequest::class, 'customer_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CreditTransaction::class, 'customer_id');
    }

    public function wholesalePricing(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\WholesalePricing::class, 'customer_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'customer_id');
    }
}
