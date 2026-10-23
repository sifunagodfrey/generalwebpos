<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_missing_stock_requests.
 * Generated from the supplied Suweka POS migrations.
 */
class MissingStockRequest extends Model
{
    protected $table = 'su_missing_stock_requests';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'product_name',
        'customer_id',
        'requested_by_staff_id',
        'note',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class, 'customer_id');
    }

    public function requestedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'requested_by_staff_id');
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
