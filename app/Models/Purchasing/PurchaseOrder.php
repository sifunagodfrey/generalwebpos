<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_purchase_orders.
 * Generated from the supplied Suweka POS migrations.
 */
class PurchaseOrder extends Model
{
    protected $table = 'su_purchase_orders';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'supplier_id',
        'status',
        'is_auto_generated',
        'received_at',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'is_auto_generated' => 'boolean',
            'created_at' => 'datetime',
            'received_at' => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Suppliers\Supplier::class, 'supplier_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\SupplierTransaction::class, 'purchase_order_id');
    }
}
