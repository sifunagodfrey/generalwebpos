<?php

namespace App\Models\Cashbox;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_shifts.
 * Generated from the supplied Suweka POS migrations.
 */
class Shift extends Model
{
    protected $table = 'su_shifts';
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'opening_cash',
        'opening_mobile_money',
        'closing_cash_counted',
        'closing_mobile_money_counted',
        'expected_cash',
        'expected_mobile_money',
        'cash_variance',
        'mobile_money_variance',
        'opened_at',
        'closed_at',
        'is_closed',
        'business_id',
        'branch_id',
    ];

    protected function casts(): array
    {
        return [
            'opening_cash' => 'decimal:2',
            'opening_mobile_money' => 'decimal:2',
            'closing_cash_counted' => 'decimal:2',
            'closing_mobile_money_counted' => 'decimal:2',
            'expected_cash' => 'decimal:2',
            'expected_mobile_money' => 'decimal:2',
            'cash_variance' => 'decimal:2',
            'mobile_money_variance' => 'decimal:2',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'is_closed' => 'boolean',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff\Staff::class, 'staff_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Business::class, 'business_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Business\Branch::class, 'branch_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Sale::class, 'shift_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\PaymentMethod::class, 'shift_id');
    }

    public function cashBox(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\CashBox::class, 'shift_id');
    }

    public function networkShifts(): HasMany
    {
        return $this->hasMany(\App\Models\Network\NetworkShift::class, 'shift_id');
    }
}
