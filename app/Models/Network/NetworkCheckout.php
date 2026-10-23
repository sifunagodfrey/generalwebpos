<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_network_checkouts.
 * Generated from the supplied Suweka POS migrations.
 */
class NetworkCheckout extends Model
{
    protected $table = 'su_network_checkouts';
    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'checkout_key',
        'staff_id',
        'basket',
        'quote',
        'response',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Network\NetworkDevice::class, 'device_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }
}
