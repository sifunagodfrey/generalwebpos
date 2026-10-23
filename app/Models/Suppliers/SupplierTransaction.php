<?php

namespace App\Models\Suppliers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_supplier_transactions.
 * Generated from the supplied Suweka POS migrations.
 */
class SupplierTransaction extends Model
{
    protected $table = 'su_supplier_transactions';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'type',
        'amount',
        'note',
        'staff_id',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Suppliers\Supplier::class, 'supplier_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Purchasing\PurchaseOrder::class, 'purchase_order_id');
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
