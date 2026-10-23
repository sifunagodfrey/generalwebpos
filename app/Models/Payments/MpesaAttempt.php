<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_mpesa_attempts.
 * Generated from the supplied Suweka POS migrations.
 */
class MpesaAttempt extends Model
{
    protected $table = 'su_mpesa_attempts';
    public $timestamps = false;

    protected $fillable = [
        'checkout_key',
        'request_key',
        'remote_id',
        'payload',
        'status',
    ];
}
