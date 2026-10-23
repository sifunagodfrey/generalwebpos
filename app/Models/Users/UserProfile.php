<?php

/*
 * ---------------------------------------------------------------------------
 * Supplementary profile details, kept separate from the login record
 * because they are edited far less often than a name, email or phone.
 * Table: su_user_profiles
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_user_profiles';

    /* Every column an administrator may write */
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'profile_picture_path',
        'secondary_phone',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /* Absolute url of the profile photo, or null when none was uploaded */
    public function getProfilePictureUrlAttribute(): ?string
    {
        return $this->profile_picture_path ? asset('uploads/users/' . $this->profile_picture_path) : null;
    }
}
