<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_product_variants.
 * Generated from the supplied Suweka POS migrations.
 */
class ProductVariant extends Model
{
    protected $table = 'su_product_variants';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'name',
        'unit_of_measure_id',
        'cost_price',
        'selling_price',
        'discount_price',
        'stock_quantity',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'stock_quantity' => 'decimal:4',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\UnitOfMeasure::class, 'unit_of_measure_id');
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
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'variant_id');
    }

    public function discountsPromotions(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\DiscountPromotion::class, 'variant_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrderItem::class, 'variant_id');
    }

    public function wholesalePricing(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\WholesalePricing::class, 'variant_id');
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockAdjustment::class, 'variant_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockBatch::class, 'variant_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductImage::class, 'variant_id');
    }

    public function barcodeLabels(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\BarcodeLabel::class, 'variant_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DocumentItem::class, 'variant_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class, 'variant_id');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCountItem::class, 'variant_id');
    }

    public function stockTransferItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransferItem::class, 'variant_id');
    }
}
