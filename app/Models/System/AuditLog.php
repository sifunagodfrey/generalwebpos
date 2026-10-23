<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_audit_log.
 * Generated from the supplied Suweka POS migrations.
 */
class AuditLog extends Model
{
    protected $table = 'su_audit_log';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'staff_id',
        'action',
        'target_type',
        'target_id',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }
}
