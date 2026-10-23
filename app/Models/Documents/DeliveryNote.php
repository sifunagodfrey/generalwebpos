<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_delivery_notes.
 * Generated from the supplied Suweka POS migrations.
 */
class DeliveryNote extends Model
{
    protected $table = 'su_delivery_notes';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'sale_id',
        'staff_id',
        'status',
        'cancel_reason',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
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
