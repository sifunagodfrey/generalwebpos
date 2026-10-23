<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_adjustments.
 * Generated from the supplied Suweka POS migrations.
 */
class StockAdjustment extends Model
{
    protected $table = 'su_stock_adjustments';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'variant_id',
        'recorded_quantity',
        'counted_quantity',
        'discrepancy',
        'staff_id',
        'note',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'recorded_quantity' => 'decimal:4',
            'counted_quantity' => 'decimal:4',
            'discrepancy' => 'decimal:4',
            'created_at' => 'datetime',
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
}
