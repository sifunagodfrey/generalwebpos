<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_document_items.
 * Generated from the supplied Suweka POS migrations.
 */
class DocumentItem extends Model
{
    protected $table = 'su_document_items';
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'product_id',
        'variant_id',
        'name',
        'quantity',
        'unit_price',
        'tax_type',
        'tax_amount',
        'line_total',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Documents\Document::class, 'document_id');
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
