<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_wholesale_pricing.
 * Generated from the supplied Suweka POS migrations.
 */
class WholesalePricing extends Model
{
    protected $table = 'su_wholesale_pricing';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'variant_id',
        'customer_id',
        'min_quantity',
        'price',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'min_quantity' => 'decimal:4',
            'price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
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
}
