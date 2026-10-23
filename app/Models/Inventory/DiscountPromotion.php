<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_discounts_promotions.
 * Generated from the supplied Suweka POS migrations.
 */
class DiscountPromotion extends Model
{
    protected $table = 'su_discounts_promotions';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'variant_id',
        'discount_percent',
        'discount_amount',
        'starts_at',
        'ends_at',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
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

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }
}
