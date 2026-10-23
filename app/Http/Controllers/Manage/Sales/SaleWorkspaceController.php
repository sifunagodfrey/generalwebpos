<?php
namespace App\Http\Controllers\Manage\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\Sales\CheckoutRequest;
use App\Services\Sales\SaleCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleWorkspaceController extends Controller
{
    private function access(Request $request, string $permission): void
    {
        $user = $request->user();
        abort_unless(
            $user && method_exists($user, 'hasPermission') && ($user->hasPermission($permission) || $user->hasPermission('pos.sales.manage')),
            403
        );
    }

    private function context(string $page, array $extra = []): array
    {
        return $extra + [
            'modules' => config('suweka_ui'),
            'module' => 'sale',
            'page' => $page,
            'item' => collect(config('suweka_ui.sale.items'))->first(fn ($i) => $i[0] === $page) ?? ['new', 'New sale'],
        ];
    }

    public function navigate(Request $request, SaleCheckoutService $service, string $page = 'new')
    {
        return match ($page) {
            'new' => $this->index($request, $service),
            'payment' => $this->payment($request, $service),
            'held' => $this->held($request),
            'history' => $this->history($request),
            'backorders' => $this->backorders($request),
            'returns' => $this->returns($request),
            'discounts' => $this->discounts($request),
            default => abort(404),
        };
    }

    public function index(Request $request, SaleCheckoutService $service)
    {
        $this->access($request, 'pos.sales.view');

        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);

        $query = DB::table('su_products as p')
            ->leftJoin('su_product_variants as v', function ($join) {
                $join->on('v.product_id', '=', 'p.id')->where('p.has_variants', '=', 1);
            })
            ->where('p.is_active', 1)
            ->where(function ($q) {
                $q->where('p.has_variants', 0)->orWhereNotNull('v.id');
            })
            ->select('p.id', 'p.tax_type', 'p.name as product_name', 'v.id as variant_id', 'v.name as variant_name')
            ->selectRaw("CASE WHEN v.id IS NULL THEN p.name ELSE CONCAT(p.name, ' - ', v.name) END AS name")
            ->selectRaw('CASE WHEN v.id IS NULL THEN p.selling_price ELSE v.selling_price END AS selling_price')
            ->selectRaw('CASE WHEN v.id IS NULL THEN p.discount_price ELSE v.discount_price END AS discount_price')
            ->selectSub(function ($q) {
                $q->from('su_stock_batches as b')
                    ->selectRaw('COALESCE(SUM(b.quantity),0)')
                    ->whereColumn('b.product_id', 'p.id')
                    ->where(function ($sub) {
                        $sub->where(function ($x) {
                            $x->whereNull('v.id')->whereNull('b.variant_id');
                        })->orWhere(function ($x) {
                            $x->whereNotNull('v.id')->whereColumn('b.variant_id', 'v.id');
                        });
                    })
                    ->where(function ($x) {
                        $x->whereNull('b.expiry_date')->orWhereDate('b.expiry_date', '>=', today());
                    });
            }, 'stock');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'like', '%' . $search . '%')
                    ->orWhere('v.name', 'like', '%' . $search . '%')
                    ->orWhereExists(function ($sub) use ($search) {
                        $sub->selectRaw('1')
                            ->from('su_barcode_labels as c')
                            ->where('c.is_assigned', 1)
                            ->whereColumn('c.product_id', 'p.id')
                            ->where(function ($match) {
                                $match->whereColumn('c.variant_id', 'v.id')
                                    ->orWhere(function ($none) {
                                        $none->whereNull('v.id')->whereNull('c.variant_id');
                                    });
                            })
                            ->where('c.code', 'like', '%' . $search . '%');
                    });
            });
        }

        $products = $query->orderBy('p.name')->orderBy('v.name')->paginate(60)->withQueryString();
        $customers = DB::table('su_customers')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone']);
        $staffId = $service->staffId((int) $request->user()->id);
        $vat = $service->vat();
        $shift = $staffId
            ? DB::table('su_shifts')->where('staff_id', $staffId)->where('is_closed', 0)->latest('id')->first()
            : null;

        return view('manage.sales.checkout.index', $this->context('new', compact('products', 'customers', 'search', 'staffId', 'shift', 'vat')));
    }

    public function payment(Request $request, SaleCheckoutService $service)
    {
        $this->access($request, 'pos.sales.view');

        $customers = DB::table('su_customers')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone', 'credit_balance', 'credit_limit']);
        $staffId = $service->staffId((int) $request->user()->id);
        $vat = $service->vat();
        $shift = $staffId
            ? DB::table('su_shifts')->where('staff_id', $staffId)->where('is_closed', 0)->latest('id')->first()
            : null;
        // ---------------
        // No open shift, send the cashier back to the sale screen
        // ---------------
        if (!$shift) {
            return redirect()->route('manage.workspace.sale', ['page' => 'new'])->with('status', 'Open a shift before making a sale.');
        }
        return view('manage.sales.checkout.payment', $this->context('new', compact('customers', 'staffId', 'shift', 'vat')));
    }

    public function barcode(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $code = trim((string) $request->query('code', ''));
        abort_if($code === '' || mb_strlen($code) > 255, 422, 'Invalid barcode');
        $label = DB::table('su_barcode_labels')->where('code', $code)->where('is_assigned', 1)->first();
        if (!$label || !$label->product_id) return response()->json(['message' => 'Barcode not assigned'], 404);

        $p = DB::table('su_products')->where('id', $label->product_id)->where('is_active', 1)->first();
        if (!$p) return response()->json(['message' => 'Product is not active'], 404);

        $v = null;
        if ($p->has_variants) {
            if (!$label->variant_id) return response()->json(['message' => 'Barcode must identify a variant'], 422);
            $v = DB::table('su_product_variants')->where('id', $label->variant_id)->where('product_id', $p->id)->first();
            if (!$v) return response()->json(['message' => 'Variant not found'], 404);
        }

        $stock = DB::table('su_stock_batches')->where('product_id', $p->id)
            ->when($v, fn ($q) => $q->where('variant_id', $v->id), fn ($q) => $q->whereNull('variant_id'))
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', today());
            })
            ->sum('quantity');

        return response()->json([
            'id' => $p->id,
            'variant_id' => $v?->id,
            'name' => $p->name . ($v ? ' - ' . $v->name : ''),
            'price' => (float) ($v?->selling_price ?? $p->selling_price),
            'stock' => (float) $stock,
            'tax_type' => $p->tax_type,
        ]);
    }

    public function quote(CheckoutRequest $request, SaleCheckoutService $service)
    {
        $this->access($request, 'pos.sales.view');
        $quote = $service->quote($request->validated());
        return response()->json(collect($quote)->except('lines')->all() + [
            'lines' => array_map(fn ($l) => collect($l)->except('batches')->all(), $quote['lines']),
        ]);
    }

    public function submit(CheckoutRequest $request, SaleCheckoutService $service)
    {
        $complete = $request->input('action') === 'complete';
        abort_unless(in_array($request->input('action'), ['hold', 'complete']), 422);
        $this->access($request, $complete ? 'pos.sales.create' : 'pos.sales.hold');
        $id = $service->save($request->validated(), (int) $request->user()->id, $complete);
        return response()->json([
            'ok' => true,
            'sale_id' => $id,
            'redirect' => $complete ? route('manage.sales.receipt', $id) : route('manage.sales.held'),
        ]);
    }

    public function held(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $staffId = app(SaleCheckoutService::class)->staffId((int) $request->user()->id);
        $records = DB::table('su_sales')->where('status', 'held')->when($staffId, fn ($q) => $q->where('staff_id', $staffId), fn ($q) => $q->whereRaw('1=0'))->orderByDesc('held_at')->paginate(20);
        return view('manage.sales.held-sales.index', $this->context('held', compact('records')));
    }

    public function recall(Request $request, int $saleId)
    {
        $this->access($request, 'pos.sales.view');
        $staffId = app(SaleCheckoutService::class)->staffId((int) $request->user()->id);
        $sale = DB::table('su_sales')->where('id', $saleId)->where('status', 'held')->where('staff_id', $staffId)->first();
        abort_unless($sale, 404);

        $rows = DB::table('su_sale_items as i')
            ->join('su_products as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('su_product_variants as v', 'v.id', '=', 'i.variant_id')
            ->where('i.sale_id', $saleId)
            ->orderBy('i.id')
            ->get(['i.product_id', 'i.variant_id', 'i.quantity', 'i.unit_price', 'p.name as product_name', 'p.tax_type', 'v.name as variant_name']);

        $lines = $rows->map(function ($r) {
            $stock = (float) DB::table('su_stock_batches')
                ->where('product_id', $r->product_id)
                ->when($r->variant_id !== null, fn ($q) => $q->where('variant_id', $r->variant_id), fn ($q) => $q->whereNull('variant_id'))
                ->where(fn ($q) => $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', today()))
                ->sum('quantity');
            return [
                'product_id'      => (int) $r->product_id,
                'variant_id'      => $r->variant_id !== null ? (int) $r->variant_id : null,
                'name'            => $r->product_name . ($r->variant_name ? ' - ' . $r->variant_name : ''),
                'price'           => (float) $r->unit_price,
                'stock'           => $stock,
                'tax_type'        => $r->tax_type,
                'quantity'        => (float) $r->quantity,
                'manual_discount' => 0,
            ];
        })->values();

        return response()->json([
            'sale'  => ['id' => (int) $sale->id, 'customer_id' => $sale->customer_id, 'hold_label' => $sale->hold_label],
            'lines' => $lines,
        ]);
    }

    public function discard(Request $request, int $saleId, SaleCheckoutService $service)
    {
        $this->access($request, 'pos.sales.hold');
        $service->discard($saleId, (int) $request->user()->id);
        return redirect()->route('manage.sales.held')->with('status', 'Held sale discarded.');
    }

    public function history(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $search = trim((string) $request->query('q', ''));
        $records = DB::table('su_sales')->where('status', 'completed')->when($search !== '', fn ($q) => $q->where('id', (int) $search))->orderByDesc('completed_at')->paginate(25)->withQueryString();
        return view('manage.sales.history.index', $this->context('history', compact('records', 'search')));
    }

    public function receipt(Request $request, int $saleId)
    {
        $this->access($request, 'pos.sales.view');
        $sale = DB::table('su_sales')->where('id', $saleId)->where('status', 'completed')->first();
        abort_unless($sale, 404);
        $lines = DB::table('su_sale_items as i')->leftJoin('su_products as p', 'p.id', '=', 'i.product_id')->where('i.sale_id', $saleId)->select('i.*', 'p.name')->get();
        $payments = DB::table('su_payment_methods')->where('sale_id', $saleId)->get();
        return view('manage.sales.history.show', $this->context('history', compact('sale', 'lines', 'payments')));
    }

    public function backorders(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $records = DB::table('su_backorders')->orderByDesc('id')->paginate(25);
        return view('manage.sales.common.list', $this->context('backorders', ['records' => $records, 'heading' => 'Backorders', 'description' => 'Pending fulfilments']));
    }

    public function returns(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $records = DB::table('su_returns_refunds')->orderByDesc('id')->paginate(25);
        return view('manage.sales.common.list', $this->context('returns', ['records' => $records, 'heading' => 'Returns', 'description' => 'Recorded returns and refunds']));
    }

    public function discounts(Request $request)
    {
        $this->access($request, 'pos.sales.view');
        $records = DB::table('su_discounts_promotions')->orderByDesc('id')->paginate(25);
        return view('manage.sales.common.list', $this->context('discounts', ['records' => $records, 'heading' => 'Discounts', 'description' => 'Current and historical offers']));
    }
}
