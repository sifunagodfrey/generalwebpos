<?php

/*
 * ---------------------------------------------------------------------------
 * An in panel alert for a staff member.
 * Table: su_notifications
 * ---------------------------------------------------------------------------
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_notifications';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'user_id',
        'audience',
        'causer_id',
        'causer_name',
        'title',
        'message',
        'type',
        'link',
        'ip_address',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'causer_id' => 'integer',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function causer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'causer_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /* Addressed to this administrator, or broadcast to all of them. Never a
       customer row, even one whose user_id happens to match, audience is
       what actually decides that now, not user_id alone */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('audience', 'admin')
            ->where(fn ($q) => $q->where('user_id', $userId)->orWhereNull('user_id'));
    }

    /* The snapshot survives even if the account that caused it is later removed */
    public function getActorNameAttribute(): string
    {
        if ($this->causer_name) {
            return $this->causer_name;
        }

        if ($this->causer) {
            return trim($this->causer->first_name . ' ' . $this->causer->last_name) ?: 'System';
        }

        return 'System';
    }
}
