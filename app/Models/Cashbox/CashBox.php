<?php

namespace App\Models\Cashbox;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_cash_box.
 * Generated from the supplied Suweka POS migrations.
 */
class CashBox extends Model
{
    protected $table = 'su_cash_box';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'shift_id',
        'type',
        'direction',
        'amount',
        'category_id',
        'reason',
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

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cashbox\Shift::class, 'shift_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cashbox\CashBoxCategory::class, 'category_id');
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
