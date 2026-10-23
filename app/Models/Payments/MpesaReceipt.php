<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_mpesa_receipts.
 * Generated from the supplied Suweka POS migrations.
 */
class MpesaReceipt extends Model
{
    protected $table = 'su_mpesa_receipts';
    public $timestamps = false;

    protected $fillable = [
        'remote_id',
        'merchant_id',
        'environment',
        'receipt',
        'amount_cents',
        'payload',
    ];
}
