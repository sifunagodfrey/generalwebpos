<?php

namespace App\Models\Cashbox;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_cash_box_categories.
 * Generated from the supplied Suweka POS migrations.
 */
class CashBoxCategory extends Model
{
    protected $table = 'su_cash_box_categories';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'direction',
        'name',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
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

    public function cashBox(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBox::class, 'category_id');
    }
}
