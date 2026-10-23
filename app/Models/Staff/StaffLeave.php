<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_staff_leave.
 * Generated from the supplied Suweka POS migrations.
 */
class StaffLeave extends Model
{
    protected $table = 'su_staff_leave';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'staff_id',
        'start_date',
        'end_date',
        'reason',
        'logged_by_staff_id',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function loggedByStaff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'logged_by_staff_id');
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
