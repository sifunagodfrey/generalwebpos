<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_barcode_labels.
 * Generated from the supplied Suweka POS migrations.
 */
class BarcodeLabel extends Model
{
    protected $table = 'su_barcode_labels';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'code',
        'product_id',
        'variant_id',
        'is_assigned',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'is_assigned' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventory\ProductVariant::class, 'variant_id');
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
