<?php

/*
 * ---------------------------------------------------------------------------
 * Pivot joining a role to the permissions it grants.
 * Table: su_role_permissions
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_role_permissions';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'permission_id' => 'integer',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
