<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_credit_notes.
 * Generated from the supplied Suweka POS migrations.
 */
class CreditNote extends Model
{
    protected $table = 'su_credit_notes';
    public $timestamps = false;

    protected $fillable = [
        'return_id',
        'item_name',
        'restocked',
        'unpaid_reduction',
        'credit_reversal',
        'cash_refund',
        'mobile_refund',
        'card_refund',
    ];

    protected function casts(): array
    {
        return [
            'restocked' => 'boolean',
            'unpaid_reduction' => 'decimal:2',
            'credit_reversal' => 'decimal:2',
            'cash_refund' => 'decimal:2',
            'mobile_refund' => 'decimal:2',
            'card_refund' => 'decimal:2',
        ];
    }

    public function return(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\ReturnRefund::class, 'return_id');
    }
}
