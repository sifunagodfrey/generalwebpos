<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_sale_items.
 * Generated from the supplied Suweka POS migrations.
 */
class SaleItem extends Model
{
    protected $table = 'su_sale_items';
    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'product_id',
        'variant_id',
        'batch_id',
        'quantity',
        'unit_price',
        'discount',
        'tax_amount',
        'line_total',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
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

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function backorders(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Backorder::class, 'sale_item_id');
    }

    public function returnsRefunds(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\ReturnRefund::class, 'sale_item_id');
    }
}
