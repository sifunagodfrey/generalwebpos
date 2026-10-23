<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_purchase_order_items.
 * Generated from the supplied Suweka POS migrations.
 */
class PurchaseOrderItem extends Model
{
    protected $table = 'su_purchase_order_items';
    public $timestamps = false;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'variant_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_ordered' => 'decimal:4',
            'quantity_received' => 'decimal:4',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Purchasing\PurchaseOrder::class, 'purchase_order_id');
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
