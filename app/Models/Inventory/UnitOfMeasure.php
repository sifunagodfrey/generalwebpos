<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_units_of_measure.
 * Generated from the supplied Suweka POS migrations.
 */
class UnitOfMeasure extends Model
{
    protected $table = 'su_units_of_measure';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'allows_decimal',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'allows_decimal' => 'boolean',
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

    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Product::class, 'unit_of_measure_id');
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductVariant::class, 'unit_of_measure_id');
    }
}
