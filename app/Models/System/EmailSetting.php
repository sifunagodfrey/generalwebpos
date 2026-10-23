<?php

/*
 * ---------------------------------------------------------------------------
 * Outgoing mail configuration.
 * Table: su_email_settings
 * ---------------------------------------------------------------------------
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    /* Table this model is bound to */
    protected $table = 'su_email_settings';

    public $timestamps = false;

    /* Every column an administrator or the storefront may write */
    protected $fillable = [
        'driver',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'port' => 'integer',
        'is_active' => 'boolean',
    ];
}
