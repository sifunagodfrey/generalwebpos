<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_transfer_items.
 * Generated from the supplied Suweka POS migrations.
 */
class StockTransferItem extends Model
{
    protected $table = 'su_stock_transfer_items';
    public $timestamps = false;

    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'variant_id',
        'quantity_sent',
        'quantity_received',
    ];

    protected function casts(): array
    {
        return [
            'quantity_sent' => 'decimal:4',
            'quantity_received' => 'decimal:4',
        ];
    }

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\StockTransfer::class, 'stock_transfer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
    }
}
