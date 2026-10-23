<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_batches.
 * Generated from the supplied Suweka POS migrations.
 */
class StockBatch extends Model
{
    protected $table = 'su_stock_batches';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'variant_id',
        'quantity',
        'expiry_date',
        'cost_price',
        'received_at',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'expiry_date' => 'datetime',
            'cost_price' => 'decimal:2',
            'received_at' => 'datetime',
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

    public function saleItems(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'batch_id');
    }

    public function backorders(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Backorder::class, 'fulfilled_from_batch_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class, 'batch_id');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCountItem::class, 'batch_id');
    }
}
