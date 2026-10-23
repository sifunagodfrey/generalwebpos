<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_stock_transfers.
 * Generated from the supplied Suweka POS migrations.
 */
class StockTransfer extends Model
{
    protected $table = 'su_stock_transfers';
    public $timestamps = false;

    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'requested_by_staff_id',
        'approved_by_staff_id',
        'status',
        'dispatched_at',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'dispatched_at' => 'datetime',
            'received_at' => 'datetime',
        ];
    }

    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'from_branch_id');
    }

    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'to_branch_id');
    }

    public function requestedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'requested_by_staff_id');
    }

    public function approvedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'approved_by_staff_id');
    }

    public function stockTransferItems(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransferItem::class, 'stock_transfer_id');
    }
}
