<?php

namespace App\Http\Controllers\Manage\Suppliers;

use App\Http\Controllers\Controller;
use App\Services\Sales\SaleCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierPaymentController extends Controller
{
    public function payment(Request $request, int $supplier, SaleCheckoutService $service)
    {
        $user = $request->user();
        abort_unless($user && $user->hasPermission('pos.suppliers.manage'), 403);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        $staffId = $service->staffId((int) $user->id);
        if (!$staffId) {
            throw ValidationException::withMessages(['amount' => 'Your login must be linked to an active staff record.']);
        }

        // ---------------
        // Locks the supplier row, lowers the balance and writes the ledger entry together
        // ---------------
        DB::transaction(function () use ($supplier, $staffId, $data) {
            $record = DB::table('su_suppliers')->where('id', $supplier)->lockForUpdate()->first();
            abort_unless($record, 404);

            $balance = (float) $record->balance_owed;
            $amount  = round((float) $data['amount'], 2);

            if ($amount > $balance + 0.001) {
                throw ValidationException::withMessages(['amount' => 'Payment cannot be more than the balance owed (' . number_format($balance, 2) . ').']);
            }

            DB::table('su_suppliers')->where('id', $supplier)->update([
                'balance_owed' => round(max(0, $balance - $amount), 2),
            ]);

            DB::table('su_supplier_transactions')->insert([
                'supplier_id'       => $supplier,
                'purchase_order_id' => null,
                'type'              => 'payment',
                'amount'            => $amount,
                'note'              => $data['note'] ?? null,
                'staff_id'          => $staffId,
                'business_id'       => $record->business_id,
                'branch_id'         => $record->branch_id,
                'created_at'        => now(),
            ]);
        });

        return redirect()->route('manage.suppliers.show', $supplier)->with('status', 'Payment recorded.');
    }
}