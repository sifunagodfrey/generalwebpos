<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_mpesa_applications.
 * Generated from the supplied Suweka POS migrations.
 */
class MpesaApplication extends Model
{
    protected $table = 'su_mpesa_applications';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'remote_id',
        'reservation_key',
        'sale_id',
        'synced',
    ];

    protected function casts(): array
    {
        return [
            'synced' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sales\Sale::class, 'sale_id');
    }
}
