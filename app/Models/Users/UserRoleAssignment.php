<?php

/*
 * ---------------------------------------------------------------------------
 * Pivot joining a user to their roles.
 * Table: su_user_roles
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRoleAssignment extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_user_roles';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'role_id' => 'integer',
        'assigned_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
