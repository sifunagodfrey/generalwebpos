<?php

namespace App\Http\Controllers\Manage\Customers;

use App\Http\Controllers\Controller;
use App\Services\Sales\SaleCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerCreditController extends Controller
{
    // ---------------
    // Ledger type values written to su_credit_transactions.type
    // ---------------
    private const TYPE_PAYMENT = 'payment';
    private const TYPE_DEBT = 'debt';

    public function payment(Request $request, int $customer, SaleCheckoutService $service)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'not_in:0', 'between:-99999999,99999999'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        // ---------------
        // Positive records a payment, negative adds debt (same dialog as the Flutter app)
        // ---------------
        $amount = (float) $data['amount'];
        $isDebt = $amount < 0;

        $this->apply($request, $customer, $service, $isDebt ? self::TYPE_DEBT : self::TYPE_PAYMENT, abs($amount), $data['note'] ?? null);

        return $this->done($customer, $isDebt ? 'Debt added.' : 'Payment recorded.');
    }

    public function debt(Request $request, int $customer, SaleCheckoutService $service)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        $this->apply($request, $customer, $service, self::TYPE_DEBT, (float) $data['amount'], $data['note'] ?? null);

        return $this->done($customer, 'Debt added.');
    }

    public function settle(Request $request, int $customer, SaleCheckoutService $service)
    {
        // ---------------
        // Pays off whatever the balance is at the moment of confirming
        // ---------------
        $this->apply($request, $customer, $service, self::TYPE_PAYMENT, null, 'Marked as paid');

        return $this->done($customer, 'Balance cleared.');
    }

    // ---------------
    // Locks the customer row, updates the balance and writes the ledger entry together
    // ---------------
    private function apply(Request $request, int $customerId, SaleCheckoutService $service, string $type, ?float $amount, ?string $note): void
    {
        $user = $request->user();
        abort_unless($user && $user->hasPermission('pos.customers.manage'), 403);

        $staffId = $service->staffId((int) $user->id);
        if (!$staffId) {
            throw ValidationException::withMessages(['amount' => 'Your login must be linked to an active staff record.']);
        }

        DB::transaction(function () use ($customerId, $staffId, $type, $amount, $note) {
            $customer = DB::table('su_customers')->where('id', $customerId)->lockForUpdate()->first();
            abort_unless($customer, 404);

            $balance = (float) $customer->credit_balance;

            // ---------------
            // Mark as paid has no amount, it uses the full balance
            // ---------------
            if ($amount === null) {
                $amount = $balance;
                if ($amount <= 0) {
                    throw ValidationException::withMessages(['amount' => 'This customer has no balance to clear.']);
                }
            }

            if ($type === self::TYPE_PAYMENT && $amount > $balance + 0.001) {
                throw ValidationException::withMessages(['amount' => 'Payment cannot be more than the balance owed (' . number_format($balance, 2) . ').']);
            }

            $newBalance = $type === self::TYPE_PAYMENT ? $balance - $amount : $balance + $amount;

            DB::table('su_customers')->where('id', $customerId)->update([
                'credit_balance' => round(max(0, $newBalance), 2),
            ]);

            DB::table('su_credit_transactions')->insert([
                'customer_id' => $customerId,
                'sale_id'     => null,
                'type'        => $type,
                'amount'      => round($amount, 2),
                'note'        => $note,
                'staff_id'    => $staffId,
                'created_at'  => now(),
            ]);
        });
    }

    private function done(int $customerId, string $message)
    {
        return redirect()->route('manage.customers.show', $customerId)->with('status', $message);
    }
}