<?php

namespace App\Http\Controllers\Manage\Suppliers;

use App\Http\Controllers\Controller;
use App\Services\Sales\SaleCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
    // ---------------
    // Same permission rules as the supplier pages
    // ---------------
    private function requireView(Request $request): void
    {
        $user = $request->user();
        abort_unless($user && ($user->hasPermission('pos.suppliers.view') || $user->hasPermission('pos.suppliers.manage')), 403);
    }

    private function requireManage(Request $request): void
    {
        $user = $request->user();
        abort_unless($user && $user->hasPermission('pos.suppliers.manage'), 403);
    }

    private function shared(string $page): array
    {
        return ['modules' => config('suweka_ui'), 'module' => 'suppliers', 'page' => $page];
    }

    public function index(Request $request)
    {
        return $this->listing($request, 'orders');
    }

    public function deliveries(Request $request)
    {
        return $this->listing($request, 'deliveries');
    }

    // ---------------
    // Orders list, and the same list narrowed to orders still waiting on supply
    // ---------------
    private function listing(Request $request, string $page)
    {
        $this->requireView($request);
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 120);

        $records = DB::table('su_purchase_orders as o')
            ->join('su_suppliers as s', 's.id', '=', 'o.supplier_id')
            ->when($page === 'deliveries', fn ($q) => $q->whereNull('o.received_at')->whereNotIn('o.status', ['received', 'completed', 'cancelled', 'canceled']))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $q->whereRaw("CONCAT(s.first_name, ' ', COALESCE(s.last_name, '')) like ?", [$like]);
            })
            ->orderByDesc('o.id')
            ->select('o.id', 'o.status', 'o.created_at', 'o.received_at', 's.first_name', 's.last_name')
            ->paginate(20)->withQueryString();

        return view('manage.suppliers.orders.index', $this->shared($page) + [
            'records' => $records,
            'search'  => $search,
        ]);
    }

    // ---------------
    // New order screen. Products and suppliers are sent once and filtered in the browser
    // ---------------
    public function create(Request $request)
    {
        $this->requireManage($request);

        $suppliers = DB::table('su_suppliers')->orderBy('first_name')->orderBy('last_name')->limit(500)
            ->get(['id', 'first_name', 'last_name', 'phone'])
            ->map(fn ($s) => ['id' => (int) $s->id, 'name' => trim($s->first_name . ' ' . ($s->last_name ?? '')), 'phone' => $s->phone])
            ->values();

        $products = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->where('p.is_active', 1)
            ->groupBy('p.id', 'p.name', 'p.cost_price')
            ->orderBy('p.name')->limit(500)
            ->get(['p.id', 'p.name', 'p.cost_price', DB::raw('COALESCE(SUM(b.quantity), 0) as stock')])
            ->map(fn ($p) => ['id' => (int) $p->id, 'name' => $p->name, 'cost' => (float) $p->cost_price, 'stock' => (float) $p->stock])
            ->values();

        $selected = $suppliers->firstWhere('id', (int) $request->query('supplier'));

        return view('manage.suppliers.orders.create', $this->shared('order') + [
            'suppliers' => $suppliers,
            'products'  => $products,
            'selected'  => $selected,
        ]);
    }

    // ---------------
    // Place order: one order row plus one item row per line, status pending
    // ---------------
    public function store(Request $request)
    {
        $this->requireManage($request);

        $data = $request->validate([
            'supplier_id'          => ['required', 'integer', 'exists:su_suppliers,id'],
            'lines'                => ['required', 'array', 'min:1', 'max:200'],
            'lines.*.product_id'   => ['required', 'integer', 'exists:su_products,id'],
            'lines.*.quantity'     => ['required', 'numeric', 'gt:0', 'max:99999999'],
            'lines.*.unit_cost'    => ['required', 'numeric', 'min:0', 'max:99999999'],
        ]);

        $orderId = DB::transaction(function () use ($data) {
            $supplier = DB::table('su_suppliers')->where('id', $data['supplier_id'])->first();

            $id = DB::table('su_purchase_orders')->insertGetId([
                'supplier_id'       => $supplier->id,
                'status'            => 'pending',
                'is_auto_generated' => 0,
                'received_at'       => null,
                'business_id'       => $supplier->business_id,
                'branch_id'         => $supplier->branch_id,
                'created_at'        => now(),
            ]);

            foreach ($data['lines'] as $line) {
                DB::table('su_purchase_order_items')->insert([
                    'purchase_order_id' => $id,
                    'product_id'        => (int) $line['product_id'],
                    'variant_id'        => null,
                    'quantity_ordered'  => round((float) $line['quantity'], 4),
                    'quantity_received' => 0,
                    'unit_cost'         => round((float) $line['unit_cost'], 2),
                    'business_id'       => $supplier->business_id,
                    'branch_id'         => $supplier->branch_id,
                ]);
            }

            return $id;
        });

        session()->flash('status', 'Order placed.');

        return response()->json(['redirect' => route('manage.suppliers.orders.show', $orderId)], 201);
    }

    public function show(Request $request, int $order)
    {
        $this->requireView($request);

        $record = DB::table('su_purchase_orders as o')
            ->join('su_suppliers as s', 's.id', '=', 'o.supplier_id')
            ->where('o.id', $order)
            ->first(['o.*', 's.first_name', 's.last_name']);
        abort_unless($record, 404);

        $items = DB::table('su_purchase_order_items as i')
            ->join('su_products as p', 'p.id', '=', 'i.product_id')
            ->where('i.purchase_order_id', $order)
            ->orderBy('i.id')
            ->get(['i.id', 'i.product_id', 'i.quantity_ordered', 'i.quantity_received', 'i.unit_cost', 'p.name']);

        return view('manage.suppliers.orders.show', $this->shared('order') + [
            'order' => $record,
            'items' => $items,
        ]);
    }

    // ---------------
    // Receive stock. Each line adds a stock batch and a movement, the supplier balance goes up by
    // the cost of what arrived, and the order closes once every line is fully in
    // ---------------
    public function receive(Request $request, int $order, SaleCheckoutService $service)
    {
        $this->requireManage($request);

        $data = $request->validate([
            'receive'   => ['required', 'array'],
            'receive.*' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
        ]);

        $staffId = $service->staffId((int) $request->user()->id);
        if (!$staffId) {
            throw ValidationException::withMessages(['receive' => 'Your login must be linked to an active staff record.']);
        }

        $complete = DB::transaction(function () use ($order, $data, $staffId) {
            $po = DB::table('su_purchase_orders')->where('id', $order)->lockForUpdate()->first();
            abort_unless($po, 404);

            if ($po->received_at !== null || strtolower((string) $po->status) === 'received') {
                throw ValidationException::withMessages(['receive' => 'This order has already been received.']);
            }

            $supplier = DB::table('su_suppliers')->where('id', $po->supplier_id)->lockForUpdate()->first();
            $items = DB::table('su_purchase_order_items as i')
                ->join('su_products as p', 'p.id', '=', 'i.product_id')
                ->where('i.purchase_order_id', $order)
                ->lockForUpdate()
                ->get(['i.id', 'i.product_id', 'i.variant_id', 'i.quantity_ordered', 'i.quantity_received', 'i.unit_cost', 'p.name']);

            $value = 0.0;
            $any = false;

            foreach ($items as $item) {
                $qty = round((float) ($data['receive'][$item->id] ?? 0), 4);
                if ($qty <= 0) {
                    continue;
                }

                $remaining = (float) $item->quantity_ordered - (float) $item->quantity_received;
                if ($qty > $remaining + 0.0001) {
                    throw ValidationException::withMessages(['receive' => $item->name . ': you can receive at most ' . rtrim(rtrim(number_format($remaining, 4, '.', ''), '0'), '.') . '.']);
                }

                $any = true;

                DB::table('su_purchase_order_items')->where('id', $item->id)->update([
                    'quantity_received' => round((float) $item->quantity_received + $qty, 4),
                ]);

                $batchId = DB::table('su_stock_batches')->insertGetId([
                    'product_id'  => $item->product_id,
                    'variant_id'  => $item->variant_id,
                    'quantity'    => $qty,
                    'cost_price'  => $item->unit_cost,
                    'received_at' => now(),
                ]);

                DB::table('su_stock_movements')->insert([
                    'product_id'     => $item->product_id,
                    'variant_id'     => $item->variant_id,
                    'batch_id'       => $batchId,
                    'staff_id'       => $staffId,
                    'movement_type'  => 'purchase',
                    'quantity_delta' => $qty,
                    'source_type'    => 'purchase_order',
                    'source_id'      => $order,
                    'note'           => 'Order #' . $order . ' received',
                    'created_at'     => now(),
                ]);

                $value += $qty * (float) $item->unit_cost;
            }

            if (!$any) {
                throw ValidationException::withMessages(['receive' => 'Enter a quantity to receive.']);
            }

            // ---------------
            // What arrived is now owed to the supplier
            // ---------------
            DB::table('su_suppliers')->where('id', $supplier->id)->update([
                'balance_owed' => round((float) $supplier->balance_owed + $value, 2),
            ]);

            DB::table('su_supplier_transactions')->insert([
                'supplier_id'       => $supplier->id,
                'purchase_order_id' => $order,
                'type'              => 'purchase',
                'amount'            => round($value, 2),
                'note'              => 'Order #' . $order . ' received',
                'staff_id'          => $staffId,
                'business_id'       => $supplier->business_id,
                'branch_id'         => $supplier->branch_id,
                'created_at'        => now(),
            ]);

            $open = DB::table('su_purchase_order_items')
                ->where('purchase_order_id', $order)
                ->whereColumn('quantity_received', '<', 'quantity_ordered')
                ->exists();

            if (!$open) {
                DB::table('su_purchase_orders')->where('id', $order)->update(['status' => 'received', 'received_at' => now()]);
            }

            return !$open;
        });

        return redirect()->route('manage.suppliers.orders.show', $order)
            ->with('status', $complete ? 'Order received. Stock updated.' : 'Delivery recorded. Stock updated.');
    }
}