<?php

/*
 * ---------------------------------------------------------------------------
 * Individual capabilities that can be granted to a role.
 * Table: su_permissions
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_permissions';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'name',
        'slug',
        'module',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'su_role_permissions', 'permission_id', 'role_id');
    }

    /* Permissions grouped by module, which is how the permission matrix is drawn */
    public static function grouped()
    {
        return static::orderBy('module')->orderBy('name')->get()->groupBy('module');
    }
}
