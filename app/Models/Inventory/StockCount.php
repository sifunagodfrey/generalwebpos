<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_counts.
 * Generated from the supplied Suweka POS migrations.
 */
class StockCount extends Model
{
    protected $table = 'su_stock_counts';
    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'started_by_staff_id',
        'completed_by_staff_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function startedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'started_by_staff_id');
    }

    public function completedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'completed_by_staff_id');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCountItem::class, 'stock_count_id');
    }
}
