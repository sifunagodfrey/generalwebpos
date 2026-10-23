<?php

/*
 * ---------------------------------------------------------------------------
 * Audit trail of actions taken in the admin panel.
 * Table: su_system_logs
 * ---------------------------------------------------------------------------
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_system_logs';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'reference_type',
        'reference_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'reference_id' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
