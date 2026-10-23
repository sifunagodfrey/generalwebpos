<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_movements.
 * Generated from the supplied Suweka POS migrations.
 */
class StockMovement extends Model
{
    protected $table = 'su_stock_movements';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'branch_id',
        'product_id',
        'variant_id',
        'batch_id',
        'staff_id',
        'movement_type',
        'quantity_delta',
        'source_type',
        'source_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'quantity_delta' => 'decimal:4',
            'created_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\StockBatch::class, 'batch_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }
}
