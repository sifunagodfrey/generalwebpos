<?php

namespace App\Http\Controllers\Manage\Sales;

use App\Http\Controllers\Controller;
use App\Services\Sales\SaleCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/** Secondary checkout screens follow Flutter Sales routes. No license checks. */
class SaleSubmoduleController extends Controller
{
    private function permission(Request $request, string $permission): void
    {
        $user = $request->user();
        abort_unless($user && ($user->hasPermission($permission) || $user->hasPermission('pos.sales.manage') || $user->hasRole('super_admin')), 403);
    }

    private function layout(string $page, array $data = []): array
    {
        return $data + ['modules' => config('suweka_ui'), 'module' => 'sale', 'page' => $page,
            'item' => collect(config('suweka_ui.sale.items'))->first(fn ($item) => $item[0] === $page) ?? [$page, ucfirst($page)]];
    }

    public function held(Request $request, SaleCheckoutService $service)
    {
        $this->permission($request, 'pos.sales.view');
        $staffId = $service->staffId((int) $request->user()->id);
        $records = DB::table('su_sales')->where('status', 'held')->where('staff_id', $staffId ?? -1)
            ->orderByDesc('held_at')->paginate(25);
        return view('manage.sales.held-sales.index', $this->layout('held', compact('records')));
    }

    public function heldDetail(Request $request, SaleCheckoutService $service, int $saleId)
    {
        $this->permission($request, 'pos.sales.view');
        $staffId = $service->staffId((int) $request->user()->id);
        $sale = DB::table('su_sales')->where('id', $saleId)->where('status', 'held')->where('staff_id', $staffId ?? -1)->first();
        abort_unless($sale, 404);
        $lines = DB::table('su_sale_items as i')->leftJoin('su_products as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('su_product_variants as v', 'v.id', '=', 'i.variant_id')
            ->where('i.sale_id', $saleId)->select('i.*', 'p.name as product_name', 'v.name as variant_name')->get();
        return view('manage.sales.held-sales.show', $this->layout('held', compact('sale', 'lines')));
    }

    public function history(Request $request)
    {
        $this->permission($request, 'pos.sales.view');
        $inputs = $request->validate(['q' => 'nullable|string|max:100', 'from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
        $search = trim((string) ($inputs['q'] ?? ''));
        $from = $inputs['from'] ?? '';
        $to = $inputs['to'] ?? '';
        $records = DB::table('su_sales as s')->leftJoin('su_customers as c', 's.customer_id', '=', 'c.id')
            ->where('s.status', 'completed')
            ->select('s.*', 'c.first_name', 'c.last_name')
            ->when($from, fn ($q) => $q->whereDate('s.completed_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('s.completed_at', '<=', $to))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($x) use ($search) {
                    $x->where('c.first_name', 'like', '%'.$search.'%')
                      ->orWhere('c.last_name', 'like', '%'.$search.'%');
                    if (is_numeric($search)) $x->orWhere('s.id', (int) $search);
                });
            })->orderByDesc('s.completed_at')->paginate(25)->withQueryString();
        return view('manage.sales.history.index', $this->layout('history', compact('records', 'search', 'from', 'to')));
    }

    public function backorders(Request $request)
    {
        $this->permission($request, 'pos.sales.view');
        $mode = $request->query('tab') === 'missing' ? 'missing' : 'backorders';
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        if ($mode === 'missing') {
            $records = DB::table('su_missing_stock_requests as r')->leftJoin('su_products as p', 'r.product_id', '=', 'p.id')
                ->select('r.*', 'p.name as catalog_name')
                ->when($search, fn ($q) => $q->where('r.product_name', 'like', '%'.$search.'%'))
                ->orderByDesc('r.id')->paginate(20)->withQueryString();
        } else {
            $records = DB::table('su_backorders as b')
                ->join('su_sale_items as i', 'i.id', '=', 'b.sale_item_id')
                ->leftJoin('su_products as p', 'p.id', '=', 'i.product_id')
                ->select('b.*', 'i.sale_id', 'i.product_id', 'p.name as product_name')
                ->when($search, fn ($q) => $q->where('p.name', 'like', '%'.$search.'%'))
                ->orderByDesc('b.id')->paginate(20)->withQueryString();
        }
        // ---------------
        // Customers for the Add request dialog
        // ---------------
        $customers = DB::table('su_customers')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone']);

        return view('manage.sales.backorders.index', $this->layout('backorders', compact('records', 'search', 'mode', 'customers')));
    }

    public function createMissing(Request $request, SaleCheckoutService $service)
    {
        $this->permission($request, 'pos.sales.create');
        $data = $request->validate([
            'product_name' => 'required|string|max:250', 'product_id' => 'nullable|integer|exists:su_products,id',
            'customer_id' => 'nullable|integer|exists:su_customers,id', 'quantity' => 'required|numeric|min:0.01|max:999999',
            'note' => 'nullable|string|max:1500',
        ]);
        $staffId = $service->staffId((int) $request->user()->id);
        if (!$staffId) throw ValidationException::withMessages(['staff' => 'An active staff account is required.']);

        // ---------------
        // The table has no quantity column, so it leads the note ("1.0" style)
        // ---------------
        $quantity = (float) $data['quantity'];
        $label = str_contains((string) $quantity, '.') ? (string) $quantity : number_format($quantity, 1, '.', '');
        $data['note'] = trim('Requested quantity: '.$label."\n".trim((string) ($data['note'] ?? '')));
        unset($data['quantity']);

        DB::table('su_missing_stock_requests')->insert($data + ['requested_by_staff_id' => $staffId, 'created_at' => now()]);
        return redirect()->route('manage.sales.backorders', ['tab' => 'missing'])->with('status', 'Missing stock request saved.');
    }

    // ---------------
    // Catalog matches for the Add request dialog
    // ---------------
    public function backorderProducts(Request $request)
    {
        $this->permission($request, 'pos.sales.view');
        $term = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        if ($term === '') return response()->json([]);

        return response()->json(DB::table('su_products')->where('name', 'like', '%'.$term.'%')->orderBy('name')->limit(8)->get(['id', 'name']));
    }

    public function returns(Request $request)
    {
        $this->permission($request, 'pos.sales.view');
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $records = DB::table('su_sales as s')->leftJoin('su_customers as c', 'c.id', '=', 's.customer_id')
            ->where('s.status', 'completed')->select('s.*', 'c.first_name', 'c.last_name')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('c.first_name', 'like', '%'.$search.'%')->orWhere('c.last_name', 'like', '%'.$search.'%');
                    if (is_numeric($search)) $q->orWhere('s.id', (int) $search);
                });
            })->orderByDesc('s.completed_at')->paginate(25)->withQueryString();
        return view('manage.sales.returns.index', $this->layout('returns', compact('records', 'search')));
    }

    public function returnSale(Request $request, int $saleId)
    {
        $this->permission($request, 'pos.sales.view');
        $sale = DB::table('su_sales')->where('id', $saleId)->where('status', 'completed')->first();
        abort_unless($sale, 404);
        $lines = DB::table('su_sale_items as i')->leftJoin('su_products as p', 'p.id', '=', 'i.product_id')
            ->leftJoin('su_product_variants as v', 'v.id', '=', 'i.variant_id')
            ->where('i.sale_id', $saleId)->select('i.*', 'p.name as product_name', 'v.name as variant_name')->get();
        $alreadyReturned = DB::table('su_returns_refunds')->where('sale_id', $saleId)->select('sale_item_id')
            ->selectRaw('SUM(quantity) as returned_quantity')->groupBy('sale_item_id')->pluck('returned_quantity', 'sale_item_id');
        return view('manage.sales.returns.show', $this->layout('returns', compact('sale', 'lines', 'alreadyReturned')));
    }

    public function discounts(Request $request)
    {
        $this->permission($request, 'pos.sales.view');
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $records = DB::table('su_discounts_promotions as d')->leftJoin('su_products as p', 'p.id', '=', 'd.product_id')
            ->leftJoin('su_product_variants as v', 'v.id', '=', 'd.variant_id')
            ->select('d.*', 'p.name as product_name', 'v.name as variant_name')
            ->when($search, fn ($q) => $q->where('p.name', 'like', '%'.$search.'%'))
            ->orderByDesc('d.starts_at')->paginate(25)->withQueryString();
        return view('manage.sales.discounts.index', $this->layout('discounts', compact('records', 'search')));
    }

    public function discountForm(Request $request, ?int $discountId = null)
    {
        $this->permission($request, 'pos.sales.discount');
        $discount = $discountId ? DB::table('su_discounts_promotions')->where('id', $discountId)->first() : null;
        abort_if($discountId && !$discount, 404);
        $products = DB::table('su_products')->where('is_active', 1)->orderBy('name')->get(['id', 'name', 'selling_price']);
        return view('manage.sales.discounts.form', $this->layout('discounts', compact('discount', 'products')));
    }

    public function saveDiscount(Request $request, ?int $discountId = null)
    {
        $this->permission($request, 'pos.sales.discount');
        $data = $request->validate([
            'product_id' => 'required|integer|exists:su_products,id',
            'discount_percent' => 'nullable|numeric|min:0|max:100|required_without:discount_amount',
            'discount_amount' => 'nullable|numeric|min:0|required_without:discount_percent',
            'starts_at' => 'required|date', 'ends_at' => 'required|date|after:starts_at',
        ]);
        if (filled($data['discount_percent'] ?? null) && filled($data['discount_amount'] ?? null))
            throw ValidationException::withMessages(['discount_amount' => 'Choose percentage or amount, not both.']);
        $data['discount_percent'] = filled($data['discount_percent'] ?? null) ? $data['discount_percent'] : null;
        $data['discount_amount'] = filled($data['discount_amount'] ?? null) ? $data['discount_amount'] : null;
        if ($discountId) {
            abort_unless(DB::table('su_discounts_promotions')->where('id', $discountId)->exists(), 404);
            DB::table('su_discounts_promotions')->where('id', $discountId)->update($data + ['updated_at' => now()]);
        } else {
            DB::table('su_discounts_promotions')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        }
        return redirect()->route('manage.sales.discounts')->with('status', 'Discount saved.');
    }

    public function deleteDiscount(Request $request, int $discountId)
    {
        $this->permission($request, 'pos.sales.discount');
        DB::table('su_discounts_promotions')->where('id', $discountId)->delete();
        return redirect()->route('manage.sales.discounts')->with('status', 'Discount removed.');
    }
}
