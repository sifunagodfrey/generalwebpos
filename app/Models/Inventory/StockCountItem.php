<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_count_items.
 * Generated from the supplied Suweka POS migrations.
 */
class StockCountItem extends Model
{
    protected $table = 'su_stock_count_items';
    public $timestamps = false;

    protected $fillable = [
        'stock_count_id',
        'product_id',
        'variant_id',
        'batch_id',
        'expected_quantity',
        'counted_quantity',
    ];

    protected function casts(): array
    {
        return [
            'expected_quantity' => 'decimal:4',
            'counted_quantity' => 'decimal:4',
        ];
    }

    public function stockCount(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\StockCount::class, 'stock_count_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\StockBatch::class, 'batch_id');
    }
}
