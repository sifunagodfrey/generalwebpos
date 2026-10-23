<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_brands.
 * Generated from the supplied Suweka POS migrations.
 */
class Brand extends Model
{
    protected $table = 'su_brands';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'description',
        'image_path',
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

    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\Product::class, 'brand_id');
    }
}
