<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_branches.
 * Generated from the supplied Suweka POS migrations.
 */
class Branch extends Model
{
    protected $table = 'su_branches';
    public $timestamps = false;

    protected $fillable = [
        'business_id',
        'name',
        'code',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Sale::class, 'branch_id');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\SaleItem::class, 'branch_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\PaymentMethod::class, 'branch_id');
    }

    public function backorders(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Backorder::class, 'branch_id');
    }

    public function missingStockRequests(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\MissingStockRequest::class, 'branch_id');
    }

    public function returnsRefunds(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\ReturnRefund::class, 'branch_id');
    }

    public function discountsPromotions(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\DiscountPromotion::class, 'branch_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrder::class, 'branch_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrderItem::class, 'branch_id');
    }

    public function staffLeave(): HasMany
    {
        return $this->hasMany(\App\Models\Staff\StaffLeave::class, 'branch_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CreditTransaction::class, 'branch_id');
    }

    public function wholesalePricing(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\WholesalePricing::class, 'branch_id');
    }

    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\SupplierTransaction::class, 'branch_id');
    }

    public function backups(): HasMany
    {
        return $this->hasMany(\App\Models\System\Backup::class, 'branch_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\Shift::class, 'branch_id');
    }

    public function cashBox(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBox::class, 'branch_id');
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockAdjustment::class, 'branch_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Category::class, 'branch_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Product::class, 'branch_id');
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductVariant::class, 'branch_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockBatch::class, 'branch_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductImage::class, 'branch_id');
    }

    public function barcodeLabels(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\BarcodeLabel::class, 'branch_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'branch_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DocumentItem::class, 'branch_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Receipt::class, 'branch_id');
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DeliveryNote::class, 'branch_id');
    }

    public function userBranches(): HasMany
    {
        return $this->hasMany(\App\Models\Business\UserBranche::class, 'branch_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class, 'branch_id');
    }

    public function stockCounts(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCount::class, 'branch_id');
    }

    public function stockTransfersByFromBranchId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransfer::class, 'from_branch_id');
    }

    public function stockTransfersByToBranchId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransfer::class, 'to_branch_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\Customer::class, 'branch_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\Supplier::class, 'branch_id');
    }

    public function cashBoxCategories(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBoxCategory::class, 'branch_id');
    }

    public function unitsOfMeasure(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\UnitOfMeasure::class, 'branch_id');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Brand::class, 'branch_id');
    }
}
