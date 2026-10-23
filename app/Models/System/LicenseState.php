<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_license_state.
 * Generated from the supplied Suweka POS migrations.
 */
class LicenseState extends Model
{
    protected $table = 'su_license_state';
    public $timestamps = false;

    protected $fillable = [
        'license_key',
        'pos_app_code',
        'device_fingerprint',
        'seats',
        'registrant_name',
        'status',
        'lease_payload',
        'lease_signature',
        'lease_key_id',
        'last_verified_at',
        'restricted_reason',
        'support_url',
    ];

    protected function casts(): array
    {
        return [
            'last_verified_at' => 'datetime',
        ];
    }
}
