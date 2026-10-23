<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_documents.
 * Generated from the supplied Suweka POS migrations.
 */
class Document extends Model
{
    protected $table = 'su_documents';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'type',
        'customer_id',
        'staff_id',
        'status',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'source_document_id',
        'converted_to_sale_id',
        'converted_at',
        'void_reason',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'created_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class, 'customer_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Documents\Document::class, 'source_document_id');
    }

    public function convertedToSale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'converted_to_sale_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'source_document_id');
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DocumentItem::class, 'document_id');
    }
}
