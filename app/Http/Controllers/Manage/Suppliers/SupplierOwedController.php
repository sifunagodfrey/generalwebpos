<?php

namespace App\Http\Controllers\Manage\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierOwedController extends Controller
{
    public function index(Request $request)
    {
        // ---------------
        // Same permission rule as the supplier list
        // ---------------
        $user = $request->user();
        abort_unless($user && ($user->hasPermission('pos.suppliers.view') || $user->hasPermission('pos.suppliers.manage')), 403);

        // ---------------
        // Page slug is read from the sidebar entry so the active item highlights
        // ---------------
        $modules = config('suweka_ui');
        $entry = collect($modules['suppliers']['items'] ?? [])->first(fn ($i) => strcasecmp((string) ($i[1] ?? ''), 'Payments owed') === 0);

        $records = DB::table('su_suppliers')
            ->where('balance_owed', '>', 0)
            ->orderByDesc('balance_owed')
            ->paginate(50);

        return view('manage.suppliers.owed', [
            'modules' => $modules,
            'module'  => 'suppliers',
            'page'    => $entry[0] ?? 'owed',
            'records' => $records,
        ]);
    }
}