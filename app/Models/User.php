<?php

/*
 * ---------------------------------------------------------------------------
 * A staff member who can sign in: an agent, supervisor or Super Admin.
 * Table: su_users
 *
 * Roles are held in a pivot rather than a column so one account can carry
 * several, for example a supervisor who also takes calls. Permission
 * checks read the union of every assigned role and are cached for the request.
 * ---------------------------------------------------------------------------
 */

namespace App\Models;

use App\Models\Users\UserRole;
use App\Models\Users\UserRoleAssignment;
use App\Models\Users\UserProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'su_users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'softphone_client_name',
        'is_available',
        'last_seen_at',
        'password',
        'status',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'twofa_code',
        'password_reset_code',
    ];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'is_available' => 'boolean',
            'twofa_expires_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ---------------------------------------------------------------------
       Naming
    --------------------------------------------------------------------- */

    /* Full name, so $user->name keeps working everywhere it is already used */
    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /* Initials for an avatar placeholder */
    public function getInitialsAttribute(): string
    {
        return strtoupper(
            substr((string) $this->first_name, 0, 1) . substr((string) $this->last_name, 0, 1)
        );
    }

    /* ---------------------------------------------------------------------
       Relations
    --------------------------------------------------------------------- */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'su_user_roles', 'user_id', 'role_id');
    }

    /* Primary role resolved through the pivot, so $user->role?->slug still reads */
    public function role()
    {
        return $this->hasOneThrough(
            UserRole::class,
            UserRoleAssignment::class,
            'user_id',
            'id',
            'id',
            'role_id'
        );
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /* ---------------------------------------------------------------------
       Query scopes
    --------------------------------------------------------------------- */

    /* Every account in this system is staff, kept as a scope so a status
       based exclusion can be added later without touching every call site */
    public function scopeStaff($query)
    {
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /* ---------------------------------------------------------------------
       Roles
    --------------------------------------------------------------------- */

    public function getRoleIdAttribute(): ?int
    {
        return $this->role?->id;
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /* ---------------------------------------------------------------------
       Permissions

       Loaded once per request as the union of every assigned role. A super
       administrator short circuits every check.
    --------------------------------------------------------------------- */

    protected $cachedPermissions = null;

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->cachedPermissions === null) {
            $this->cachedPermissions = $this->roles()
                ->with('permissions')
                ->get()
                ->flatMap(fn ($role) => $role->permissions->pluck('slug'))
                ->unique()
                ->values()
                ->all();
        }

        return in_array($slug, $this->cachedPermissions, true);
    }

    public function hasAnyPermission(array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if (! $this->hasPermission($slug)) {
                return false;
            }
        }

        return true;
    }
}
