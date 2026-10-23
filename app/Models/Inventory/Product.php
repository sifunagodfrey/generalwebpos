<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_products.
 * Generated from the supplied Suweka POS migrations.
 */
class Product extends Model
{
    protected $table = 'su_products';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'unit_of_measure_id',
        'cost_price',
        'selling_price',
        'discount_price',
        'tax_type',
        'description',
        'has_variants',
        'is_active',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'has_variants' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Brand::class, 'brand_id');
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
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'product_id');
    }

    public function missingStockRequests(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\MissingStockRequest::class, 'product_id');
    }

    public function discountsPromotions(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\DiscountPromotion::class, 'product_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrderItem::class, 'product_id');
    }

    public function wholesalePricing(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\WholesalePricing::class, 'product_id');
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockAdjustment::class, 'product_id');
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductVariant::class, 'product_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockBatch::class, 'product_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductImage::class, 'product_id');
    }

    public function barcodeLabels(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\BarcodeLabel::class, 'product_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DocumentItem::class, 'product_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class, 'product_id');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCountItem::class, 'product_id');
    }

    public function stockTransferItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransferItem::class, 'product_id');
    }
}
