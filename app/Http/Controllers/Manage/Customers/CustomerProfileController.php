<?php

namespace App\Http\Controllers\Manage\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
{
    public function show(Request $request, int $customer)
    {
        // ---------------
        // Same permission rule as the customer list
        // ---------------
        $user = $request->user();
        abort_unless($user && ($user->hasPermission('pos.customers.view') || $user->hasPermission('pos.customers.manage')), 403);

        $record = DB::table('su_customers')->where('id', $customer)->first();
        abort_unless($record, 404);

        // ---------------
        // Latest completed sales and credit ledger for this customer
        // ---------------
        $sales = DB::table('su_sales')
            ->where('customer_id', $customer)->where('status', 'completed')
            ->orderByDesc('completed_at')->limit(10)->get();

        $ledger = DB::table('su_credit_transactions')
            ->where('customer_id', $customer)
            ->orderByDesc('id')->limit(50)->get();

        return view('manage.customers.show', [
            'modules' => config('suweka_ui'),
            'module'  => 'customers',
            'page'    => 'index',
            'customer' => $record,
            'sales'   => $sales,
            'ledger'  => $ledger,
        ]);
    }
}