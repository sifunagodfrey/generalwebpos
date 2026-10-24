<?php

namespace App\Http\Controllers\Manage\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditLedgerController extends Controller
{
    public function index(Request $request)
    {
        // ---------------
        // Same permission rule as the customer list
        // ---------------
        $user = $request->user();
        abort_unless($user && ($user->hasPermission('pos.customers.view') || $user->hasPermission('pos.customers.manage')), 403);

        $inputs = $request->validate([
            'q'       => 'nullable|string|max:100',
            'balance' => 'nullable|in:all,over_limit',
            'sort'    => 'nullable|in:highest,lowest,name',
        ]);
        $search  = trim((string) ($inputs['q'] ?? ''));
        $balance = $inputs['balance'] ?? 'all';
        $sort    = $inputs['sort'] ?? 'highest';

        // ---------------
        // Header figures cover everyone who owes, whatever the filters
        // ---------------
        $owing = DB::table('su_customers')->where('credit_balance', '>', 0);
        $owingCount = (clone $owing)->count();
        $owingTotal = (float) (clone $owing)->sum('credit_balance');

        $records = $owing
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $q->where(function ($x) use ($like) {
                    $x->where('first_name', 'like', $like)
                      ->orWhere('last_name', 'like', $like)
                      ->orWhere('phone', 'like', $like)
                      ->orWhere('email', 'like', $like)
                      ->orWhereRaw("CONCAT(first_name, ' ', COALESCE(last_name, '')) like ?", [$like]);
                });
            })
            ->when($balance === 'over_limit', fn ($q) => $q->whereNotNull('credit_limit')->whereColumn('credit_balance', '>', 'credit_limit'))
            ->when($sort === 'highest', fn ($q) => $q->orderByDesc('credit_balance'))
            ->when($sort === 'lowest', fn ($q) => $q->orderBy('credit_balance'))
            ->when($sort === 'name', fn ($q) => $q->orderBy('first_name')->orderBy('last_name'))
            ->paginate(50)->withQueryString();

        return view('manage.customers.ledger', [
            'modules'    => config('suweka_ui'),
            'module'     => 'customers',
            'page'       => 'ledger',
            'records'    => $records,
            'owingCount' => $owingCount,
            'owingTotal' => $owingTotal,
            'search'     => $search,
            'balance'    => $balance,
            'sort'       => $sort,
        ]);
    }
}