<?php

namespace App\Http\Controllers\Manage\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierProfileController extends Controller
{
    public function show(Request $request, int $supplier)
    {
        // ---------------
        // Same permission rule as the supplier list
        // ---------------
        $user = $request->user();
        abort_unless($user && ($user->hasPermission('pos.suppliers.view') || $user->hasPermission('pos.suppliers.manage')), 403);

        $record = DB::table('su_suppliers')->where('id', $supplier)->first();
        abort_unless($record, 404);

        // ---------------
        // Orders with line count and value, newest first. Pending means not received and not cancelled
        // ---------------
        $orderQuery = fn () => DB::table('su_purchase_orders as o')
            ->leftJoin('su_purchase_order_items as i', 'i.purchase_order_id', '=', 'o.id')
            ->where('o.supplier_id', $supplier)
            ->groupBy('o.id', 'o.status', 'o.created_at', 'o.received_at')
            ->orderByDesc('o.id')->limit(20);
        $columns = ['o.id', 'o.status', 'o.created_at', 'o.received_at',
            DB::raw('COUNT(i.product_id) as item_count'),
            DB::raw('COALESCE(SUM(i.quantity_ordered * i.unit_cost), 0) as total')];

        $orders  = $orderQuery()->get($columns);
        $pending = $orderQuery()->whereNull('o.received_at')->whereNotIn('o.status', ['cancelled', 'canceled', 'received', 'completed'])->get($columns);

        // ---------------
        // Products bought from this supplier and the supplier ledger
        // ---------------
        $products = DB::table('su_purchase_order_items as i')
            ->join('su_purchase_orders as o', 'o.id', '=', 'i.purchase_order_id')
            ->join('su_products as p', 'p.id', '=', 'i.product_id')
            ->where('o.supplier_id', $supplier)
            ->orderBy('i.id')
            ->get(['p.id', 'p.name', 'i.unit_cost'])
            ->groupBy('id')
            ->map(fn ($rows) => (object) ['id' => $rows->first()->id, 'name' => $rows->first()->name, 'cost' => $rows->last()->unit_cost])
            ->values()->take(50);

        $ledger = DB::table('su_supplier_transactions')
            ->where('supplier_id', $supplier)
            ->orderByDesc('id')->limit(50)->get();

        return view('manage.suppliers.show', [
            'modules'  => config('suweka_ui'),
            'module'   => 'suppliers',
            'page'     => 'profile',
            'supplier' => $record,
            'orders'   => $orders,
            'pending'  => $pending,
            'products' => $products,
            'ledger'   => $ledger,
        ]);
    }
}