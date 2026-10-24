<?php
namespace App\Http\Controllers\Manage\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\UploadService;
class InventoryWorkspaceController extends Controller
{
    private function authorizePos(Request $request, string $permission = 'pos.products.view'): void
    {
        $user = $request->user();
        abort_unless($user && method_exists($user, 'hasPermission') && ($user->hasPermission($permission) || $user->hasPermission('pos.products.manage')), 403);
    }

    private function view(Request $request, string $page, string $template, array $values = [])
    {
        $item = collect(config('suweka_ui.inventory.items', []))->first(fn ($v) => $v[0] === $page) ?? [$page, ucfirst($page), 'box'];
        return view($template, $values + ['modules' => config('suweka_ui'), 'module' => 'inventory', 'page' => $page, 'item' => $item]);
    }

    private function lookups(): array
    {
        return [
            'categories' => DB::table('su_categories')->orderBy('name')->get(['id', 'name']),
            'brands' => DB::table('su_brands')->orderBy('name')->get(['id', 'name']),
            'units' => DB::table('su_units_of_measure')->orderBy('name')->get(['id', 'name']),
        ];
    }

    public function section(Request $request, string $page = 'products')
    {
        $this->authorizePos($request);
        return match ($page) {
            'products' => $this->products($request),
            'create' => $this->create($request),
            'categories', 'brands' => $this->lookupsIndex($request, $page),
            'alerts', 'stocktake', 'backorders', 'reports' => $this->other($request, $page),
            default => abort(404),
        };
    }

    public function products(Request $request)
    {
        $this->authorizePos($request);
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 120);
        $query = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id', 'p.name', 'p.selling_price', 'p.has_variants', 'p.is_active', 'p.category_id')
            ->selectRaw('COALESCE(SUM(CASE WHEN b.expiry_date IS NULL OR b.expiry_date >= CURRENT_DATE THEN b.quantity ELSE 0 END),0) AS stock')
            ->groupBy('p.id', 'p.name', 'p.selling_price', 'p.has_variants', 'p.is_active', 'p.category_id');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'like', '%' . addcslashes($search, '%_\\') . '%')
                    ->orWhereExists(function ($x) use ($search) {
                        $x->selectRaw('1')->from('su_product_variants as v')->whereColumn('v.product_id', 'p.id')
                            ->where('v.name', 'like', '%' . addcslashes($search, '%_\\') . '%');
                    });
            });
        }
        $products = $query->orderBy('p.name')->paginate(20)->withQueryString();
        return $this->view($request, 'products', 'manage.inventory.products.catalog', compact('products', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorizePos($request, 'pos.products.create');
        return $this->view($request, 'create', 'manage.inventory.products.form', $this->lookups() + ['product' => null]);
    }

    public function show(Request $request, int $product)
    {
        $this->authorizePos($request);
        $record = DB::table('su_products')->where('id', $product)->first();
        abort_unless($record, 404);
        $batches = DB::table('su_stock_batches')->where('product_id', $product)->orderByDesc('received_at')->get();
        $variants = DB::table('su_product_variants')->where('product_id', $product)->get();
        $stock = $batches->sum('quantity');
        return $this->view($request, 'products', 'manage.inventory.products.show', compact('record', 'batches', 'variants', 'stock'));
    }

    public function edit(Request $request, int $product)
    {
        $this->authorizePos($request, 'pos.products.update');
        $record = DB::table('su_products')->where('id', $product)->first();
        abort_unless($record, 404);
        return $this->view($request, 'products', 'manage.inventory.products.form', $this->lookups() + ['product' => $record]);
    }

    private function validateProduct(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'integer', 'exists:su_categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:su_brands,id'],
            'unit_of_measure_id' => ['nullable', 'integer', 'exists:su_units_of_measure,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:selling_price'],
            'tax_type' => ['required', Rule::in(['standard_rated', 'zero_rated', 'exempt'])],
            'description' => ['nullable', 'string', 'max:4000'],
            'is_active' => ['sometimes', 'boolean'],
            'has_variants' => ['sometimes', 'boolean'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'expiry_alerts' => ['sometimes', 'boolean'],
            'starting_stock' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    public function store(Request $request, ProductService $service)
    {
        $this->authorizePos($request, 'pos.products.create');
        $data = $this->validateProduct($request);

        $data['has_variants'] = $request->boolean('has_variants');
        $data['is_active'] = $request->boolean('is_active', true);

        $barcode = trim((string) ($data['barcode'] ?? ''));
        $startingStock = (float) ($data['starting_stock'] ?? 0);

        // low_stock_threshold / expiry_alerts are accepted from the form but not
        // persisted yet; there are no matching columns on su_products.
        unset($data['barcode'], $data['starting_stock'], $data['low_stock_threshold'], $data['expiry_alerts']);

        $product = $service->create($data);

        if ($barcode !== '' && ! DB::table('su_barcode_labels')->where('code', $barcode)->exists()) {
            DB::table('su_barcode_labels')->insert([
                'code' => $barcode,
                'product_id' => $product->id,
                'variant_id' => null,
                'is_assigned' => 1,
                'created_at' => now(),
            ]);
        }

        if ($startingStock > 0) {
            DB::table('su_stock_batches')->insert([
                'product_id' => $product->id,
                'quantity' => $startingStock,
                'received_at' => now(),
            ]);
        }

        if ($request->hasFile('photo')) {
            $media = app(UploadService::class)->store($request->file('photo'), 'products');
            DB::table('su_product_images')->insert([
                'product_id' => $product->id,
                'file_path' => $media->file_path,
                'is_primary' => 1,
            ]);
        }

        return redirect()->route('manage.inventory.product.show', $product->id)
            ->with('status', 'Product created.');
    }

    public function update(Request $request, int $product, ProductService $service)
    {
        $this->authorizePos($request, 'pos.products.update');
        $model = \App\Models\Inventory\Product::findOrFail($product);
        $data = $this->validateProduct($request, $product);

        $data['is_active'] = $request->boolean('is_active');
        $data['has_variants'] = $request->boolean('has_variants');

        $barcode = trim((string) ($data['barcode'] ?? ''));
        unset($data['barcode'], $data['starting_stock'], $data['low_stock_threshold'], $data['expiry_alerts']);

        $service->update($model, $data);

        if ($barcode !== ''
            && ! DB::table('su_barcode_labels')->where('code', $barcode)->exists()) {
            DB::table('su_barcode_labels')->insert([
                'code' => $barcode,
                'product_id' => $product,
                'variant_id' => null,
                'is_assigned' => 1,
                'created_at' => now(),
            ]);
        }

        if ($request->hasFile('photo')) {
            $media = app(UploadService::class)->store($request->file('photo'), 'products');
            DB::table('su_product_images')->insert([
                'product_id' => $product,
                'file_path' => $media->file_path,
                'is_primary' => 1,
            ]);
        }

        return redirect()->route('manage.inventory.product.show', $product)
            ->with('status', 'Product updated.');
    }
    public function lookupsIndex(Request $request, string $type)
    {
        $this->authorizePos($request);
        abort_unless(in_array($type, ['categories', 'brands'], true), 404);
        $table = $type === 'categories' ? 'su_categories' : 'su_brands';
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $records = DB::table($table)->when($search !== '', fn ($q) => $q->where('name', 'like', '%' . addcslashes($search, '%_\\') . '%'))
            ->orderBy('name')->paginate(25)->withQueryString();
        return $this->view($request, $type, 'manage.inventory.partials.lookups', compact('type', 'records', 'search'));
    }

    public function storeLookup(Request $request, string $type)
    {
        abort_unless(in_array($type, ['categories', 'brands'], true), 404);
        $this->authorizePos($request, 'pos.products.create');
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'description' => ['nullable', 'string', 'max:2000']]);
        $table = $type === 'categories' ? 'su_categories' : 'su_brands';
        DB::table($table)->insert($data + ['created_at' => now()]);
        return back()->with('status', ucfirst($type) . ' entry created.');
    }

    public function updateLookup(Request $request, string $type, int $id)
    {
        abort_unless(in_array($type, ['categories', 'brands'], true), 404);
        $this->authorizePos($request, 'pos.products.update');
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'description' => ['nullable', 'string', 'max:2000']]);
        $table = $type === 'categories' ? 'su_categories' : 'su_brands';
        abort_unless(DB::table($table)->where('id', $id)->exists(), 404);
        DB::table($table)->where('id', $id)->update($data);
        return back()->with('status', 'Entry updated.');
    }

    public function other(Request $request, string $page)
    {
        $this->authorizePos($request);
        $records = collect();
        if ($page === 'backorders') $records = DB::table('su_backorders')->orderByDesc('id')->limit(40)->get();
        if ($page === 'alerts') {
            $records = DB::table('su_products as p')->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
                ->select('p.id', 'p.name')->selectRaw('COALESCE(SUM(b.quantity),0) as stock')
                ->groupBy('p.id', 'p.name')->havingRaw('COALESCE(SUM(b.quantity),0) <= 5')->orderBy('p.name')->limit(100)->get();
        }
        return $this->view($request, $page, 'manage.inventory.partials.other', compact('page', 'records'));
    }
}
