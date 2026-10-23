<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_pos_cloud_backup_settings.
 * Generated from the supplied Suweka POS migrations.
 */
class PosCloudBackupSetting extends Model
{
    protected $table = 'su_pos_cloud_backup_settings';
    public $timestamps = false;

    protected $fillable = [
        'license_id',
        'email',
    ];
}
