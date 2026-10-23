<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_backorders.
 * Generated from the supplied Suweka POS migrations.
 */
class Backorder extends Model
{
    protected $table = 'su_backorders';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_item_id',
        'quantity_pending',
        'fulfilled_from_batch_id',
        'fulfilled_at',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_pending' => 'decimal:4',
            'fulfilled_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\SaleItem::class, 'sale_item_id');
    }

    public function fulfilledFromBatch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\StockBatch::class, 'fulfilled_from_batch_id');
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
