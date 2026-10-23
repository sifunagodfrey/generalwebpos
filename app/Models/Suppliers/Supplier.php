<?php

namespace App\Models\Suppliers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_suppliers.
 * Generated from the supplied Suweka POS migrations.
 */
class Supplier extends Model
{
    protected $table = 'su_suppliers';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'other_details',
        'balance_owed',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'balance_owed' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(\App\Models\Purchasing\PurchaseOrder::class, 'supplier_id');
    }

    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\SupplierTransaction::class, 'supplier_id');
    }
}
