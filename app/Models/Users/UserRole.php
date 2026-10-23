<?php

/*
 * ---------------------------------------------------------------------------
 * System roles that gate access to each admin module.
 * Table: su_roles
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_roles';

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_system' => 'boolean',
    ];

    /* Permissions granted by this role */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'su_role_permissions', 'role_id', 'permission_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'su_user_roles', 'role_id', 'user_id');
    }
}
