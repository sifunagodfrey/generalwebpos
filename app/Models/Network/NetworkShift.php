<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_network_shifts.
 * Generated from the supplied Suweka POS migrations.
 */
class NetworkShift extends Model
{
    protected $table = 'su_network_shifts';
    public $timestamps = false;

    protected $fillable = [
        'shift_id',
        'device_id',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Network\NetworkDevice::class, 'device_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cashbox\Shift::class, 'shift_id');
    }
}
