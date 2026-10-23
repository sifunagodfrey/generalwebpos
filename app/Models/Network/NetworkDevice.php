<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_network_devices.
 * Generated from the supplied Suweka POS migrations.
 */
class NetworkDevice extends Model
{
    protected $table = 'su_network_devices';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'token_hash',
        'status',
        'paired_at',
        'last_seen',
    ];

    protected function casts(): array
    {
        return [
            'paired_at' => 'datetime',
            'last_seen' => 'datetime',
        ];
    }

    public function networkCheckouts(): HasMany
    {
        return $this->hasMany(\App\Models\Network\NetworkCheckout::class, 'device_id');
    }

    public function networkShifts(): HasMany
    {
        return $this->hasMany(\App\Models\Network\NetworkShift::class, 'device_id');
    }
}
