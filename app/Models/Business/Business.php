<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_businesses.
 * Generated from the supplied Suweka POS migrations.
 */
class Business extends Model
{
    protected $table = 'su_businesses';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'currency',
        'timezone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Sale::class, 'business_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'business_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\PaymentMethod::class, 'business_id');
    }

    public function backorders(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Backorder::class, 'business_id');
    }

    public function missingStockRequests(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\MissingStockRequest::class, 'business_id');
    }

    public function returnsRefunds(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\ReturnRefund::class, 'business_id');
    }

    public function discountsPromotions(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\DiscountPromotion::class, 'business_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrder::class, 'business_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrderItem::class, 'business_id');
    }

    public function staffLeave(): HasMany
    {
        return $this->hasMany(\App\Models\Staff\StaffLeave::class, 'business_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CreditTransaction::class, 'business_id');
    }

    public function wholesalePricing(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\WholesalePricing::class, 'business_id');
    }

    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\SupplierTransaction::class, 'business_id');
    }

    public function backups(): HasMany
    {
        return $this->hasMany(\App\Models\System\Backup::class, 'business_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\Shift::class, 'business_id');
    }

    public function cashBox(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBox::class, 'business_id');
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockAdjustment::class, 'business_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Category::class, 'business_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Product::class, 'business_id');
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductVariant::class, 'business_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockBatch::class, 'business_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductImage::class, 'business_id');
    }

    public function barcodeLabels(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\BarcodeLabel::class, 'business_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'business_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DocumentItem::class, 'business_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Receipt::class, 'business_id');
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DeliveryNote::class, 'business_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(\App\Models\Business\Branch::class, 'business_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\Customer::class, 'business_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\Supplier::class, 'business_id');
    }

    public function cashBoxCategories(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBoxCategory::class, 'business_id');
    }

    public function unitsOfMeasure(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\UnitOfMeasure::class, 'business_id');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Brand::class, 'business_id');
    }
}
