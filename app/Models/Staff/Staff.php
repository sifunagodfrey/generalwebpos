<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent mapping for su_staff.
 * Generated from the supplied Suweka POS migrations.
 */
class Staff extends Model
{
    protected $table = 'su_staff';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'pin_hash',
        'is_active',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\Sale::class, 'staff_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\PaymentMethod::class, 'staff_id');
    }

    public function missingStockRequests(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\MissingStockRequest::class, 'requested_by_staff_id');
    }

    public function returnsRefunds(): HasMany
    {
        return $this->hasMany(\App\Models\Sales\ReturnRefund::class, 'staff_id');
    }

    public function staffLeaveByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Staff\StaffLeave::class, 'staff_id');
    }

    public function staffLeaveByLoggedByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Staff\StaffLeave::class, 'logged_by_staff_id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Customers\CreditTransaction::class, 'staff_id');
    }

    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\Suppliers\SupplierTransaction::class, 'staff_id');
    }

    public function auditLog(): HasMany
    {
        return $this->hasMany(\App\Models\System\AuditLog::class, 'staff_id');
    }

    public function backups(): HasMany
    {
        return $this->hasMany(\App\Models\System\Backup::class, 'staff_id');
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(\App\Models\Cashbox\Shift::class, 'staff_id');
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockAdjustment::class, 'staff_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\Document::class, 'staff_id');
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(\App\Models\Documents\DeliveryNote::class, 'staff_id');
    }

    public function networkCheckouts(): HasMany
    {
        return $this->hasMany(\App\Models\Network\NetworkCheckout::class, 'staff_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class, 'staff_id');
    }

    public function stockCountsByStartedByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCount::class, 'started_by_staff_id');
    }

    public function stockCountsByCompletedByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockCount::class, 'completed_by_staff_id');
    }

    public function stockTransfersByRequestedByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransfer::class, 'requested_by_staff_id');
    }

    public function stockTransfersByApprovedByStaffId(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\StockTransfer::class, 'approved_by_staff_id');
    }
}
