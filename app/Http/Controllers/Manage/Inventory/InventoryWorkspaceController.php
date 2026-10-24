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
            'categories' => $this->categoriesIndex($request),
            'brands' => $this->brandsIndex($request),
            'alerts' => $this->alerts($request),
            'stocktake' => $this->stocktake($request),
            'backorders' => $this->backorders($request), 
            'reports' => $this->other($request, $page),
            default => abort(404),
        };
    }

    public function backorders(Request $request)
    {
        $this->authorizePos($request);
        
        $mode = $request->query('tab') === 'missing' ? 'missing' : 'backorders';
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);

        if ($mode === 'missing') {
            $records = DB::table('su_missing_stock_requests as r')
                ->leftJoin('su_products as p', 'r.product_id', '=', 'p.id')
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

        $customers = DB::table('su_customers')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone']);

        // This renders the Sales view, but $this->view() forces the layout to stay in Inventory
        return $this->view($request, 'backorders', 'manage.sales.backorders.index', compact('records', 'search', 'mode', 'customers'));
    }
    public function stocktake(Request $request)
    {
        $this->authorizePos($request);
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 120);
        
        $query = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id', 'p.name', 'p.selling_price', 'p.has_variants', 'p.is_active')
            ->selectRaw('COALESCE(SUM(CASE WHEN b.expiry_date IS NULL OR b.expiry_date >= CURRENT_DATE THEN b.quantity ELSE 0 END),0) AS stock')
            ->where('p.is_active', 1)
            ->groupBy('p.id', 'p.name', 'p.selling_price', 'p.has_variants', 'p.is_active');
            
        if ($search !== '') {
            $query->where('p.name', 'like', '%' . addcslashes($search, '%_\\') . '%');
        }
        
        $products = $query->orderBy('p.name')->paginate(20)->withQueryString();
        
        // Update the view path to match your folder structure
        return $this->view($request, 'stocktake', 'manage.inventory.stocktake.index', compact('products', 'search'));
    }

    public function storeStocktake(Request $request)
    {
        $this->authorizePos($request, 'pos.products.update');
        
        $data = $request->validate([
            'product_id' => 'required|integer|exists:su_products,id',
            'counted_quantity' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1500',
        ]);

        $staffId = DB::table('su_staff')->where('user_id', $request->user()->id)->value('id');

        DB::transaction(function () use ($data, $staffId) {
            // Calculate current stock
            $currentStock = DB::table('su_stock_batches')
                ->where('product_id', $data['product_id'])
                ->where(function($q) {
                    $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', today());
                })
                ->sum('quantity');
                
            $discrepancy = $data['counted_quantity'] - $currentStock;

            // 1. Record adjustment
            DB::table('su_stock_adjustments')->insert([
                'product_id' => $data['product_id'],
                'recorded_quantity' => $currentStock,
                'counted_quantity' => $data['counted_quantity'],
                'discrepancy' => $discrepancy,
                'staff_id' => $staffId,
                'note' => $data['note'] ?? 'Stock take',
                'created_at' => now(),
            ]);

            // 2. Adjust batches (add or remove)
            if ($discrepancy > 0) {
                DB::table('su_stock_batches')->insert([
                    'product_id' => $data['product_id'],
                    'quantity' => $discrepancy,
                    'received_at' => now(),
                ]);
            } elseif ($discrepancy < 0) {
                $toRemove = abs($discrepancy);
                $batches = DB::table('su_stock_batches')
                    ->where('product_id', $data['product_id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();
                    
                foreach ($batches as $batch) {
                    if ($toRemove <= 0) break;
                    $take = min($toRemove, $batch->quantity);
                    DB::table('su_stock_batches')->where('id', $batch->id)->decrement('quantity', $take);
                    $toRemove -= $take;
                }
            }

            // 3. Stock movement
            if ($staffId) {
                DB::table('su_stock_movements')->insert([
                    'product_id' => $data['product_id'],
                    'staff_id' => $staffId,
                    'movement_type' => 'adjustment',
                    'quantity_delta' => $discrepancy,
                    'source_type' => 'stock_take',
                    'source_id' => $data['product_id'],
                    'note' => $data['note'],
                    'created_at' => now(),
                ]);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Count recorded successfully.']);
    }

    private function view(Request $request, string $page, string $template, array $values = [])
    {
        $lookupPage = str_contains($page, 'categories') ? 'categories' : $page;
        $item = collect(config('suweka_ui.inventory.items', []))->first(fn ($v) => $v[0] === $lookupPage) ?? [$lookupPage, ucfirst($lookupPage), 'box'];
        
        // Calculate alerts count for the sidebar badge
        $alertsCount = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')
            ->groupBy('p.id')
            ->havingRaw('COALESCE(SUM(b.quantity),0) <= 5')
            ->get()->count();

        return view($template, $values + [
            'modules' => config('suweka_ui'), 
            'module' => 'inventory', 
            'page' => $lookupPage, 
            'item' => $item,
            'alertsCount' => $alertsCount
        ]);
    }

    public function categoriesIndex(Request $request)
    {
        $this->authorizePos($request);
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        
        $categories = DB::table('su_categories as c')
            ->select('c.*')
            ->selectRaw('(SELECT COUNT(*) FROM su_products p WHERE p.category_id = c.id) as products_count')
            ->when($search !== '', fn ($q) => $q->where('c.name', 'like', '%' . addcslashes($search, '%_\\') . '%'))
            ->orderBy('c.name')
            ->paginate(24)
            ->withQueryString();

        return $this->view($request, 'categories', 'manage.inventory.categories.index', compact('categories', 'search'));
    }

    public function createCategory(Request $request)
    {
        $this->authorizePos($request, 'pos.products.create');
        $parents = DB::table('su_categories')->orderBy('name')->get(['id', 'name']);
        return $this->view($request, 'categories.create', 'manage.inventory.categories.form', compact('parents'));
    }

    public function storeCategory(Request $request)
    {
        $this->authorizePos($request, 'pos.products.create');
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:su_categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $media = app(UploadService::class)->store($request->file('image'), 'categories');
            $data['image_path'] = $media->file_path;
        }
        unset($data['image']);
        $data['created_at'] = now();

        DB::table('su_categories')->insert($data);

        return redirect()->route('manage.workspace.inventory', ['page' => 'categories'])
            ->with('status', 'Category created.');
    }

    public function brandsIndex(Request $request)
    {
        $this->authorizePos($request);
        $search = mb_substr(trim((string) $request->query('q', '')), 0, 100);

        $brands = DB::table('su_brands as b')
            ->select('b.*')
            ->selectRaw('(SELECT COUNT(*) FROM su_products p WHERE p.brand_id = b.id) as products_count')
            ->when($search !== '', fn ($q) => $q->where('b.name', 'like', '%' . addcslashes($search, '%_\\') . '%'))
            ->orderBy('b.name')
            ->paginate(24)
            ->withQueryString();

        return $this->view($request, 'brands', 'manage.inventory.brands.index', compact('brands', 'search'));
    }

    public function createBrand(Request $request)
    {
        $this->authorizePos($request, 'pos.products.create');
        return $this->view($request, 'brands', 'manage.inventory.brands.form');
    }

    public function storeBrand(Request $request)
    {
        $this->authorizePos($request, 'pos.products.create');

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $media = app(UploadService::class)->store($request->file('image'), 'brands');
            $data['image_path'] = $media->file_path;
        }
        unset($data['image']);
        $data['created_at'] = now();

        DB::table('su_brands')->insert($data);

        return redirect()
            ->route('manage.workspace.inventory', ['page' => 'brands'])
            ->with('status', 'Brand created.');
    }

    public function editBrand(Request $request, int $brand)
    {
        $this->authorizePos($request, 'pos.products.update');

        $record = DB::table('su_brands')->where('id', $brand)->first();
        abort_unless($record, 404);

        return $this->view($request, 'brands', 'manage.inventory.brands.form', ['brand' => $record]);
    }

    public function updateBrand(Request $request, int $brand)
    {
        $this->authorizePos($request, 'pos.products.update');
        abort_unless(DB::table('su_brands')->where('id', $brand)->exists(), 404);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $media = app(UploadService::class)->store($request->file('image'), 'brands');
            $data['image_path'] = $media->file_path;
        }
        unset($data['image']);

        DB::table('su_brands')->where('id', $brand)->update($data);

        return redirect()
            ->route('manage.workspace.inventory', ['page' => 'brands'])
            ->with('status', 'Brand updated.');
    }

    public function destroyBrand(Request $request, int $brand)
    {
        $this->authorizePos($request, 'pos.products.delete');
        abort_unless(DB::table('su_brands')->where('id', $brand)->exists(), 404);

        $usedBy = DB::table('su_products')->where('brand_id', $brand)->count();
        if ($usedBy > 0) {
            return back()->withErrors([
                'brand' => 'This brand is still linked to ' . $usedBy . ' product(s). Reassign them first.',
            ]);
        }

        DB::table('su_brands')->where('id', $brand)->delete();

        return redirect()
            ->route('manage.workspace.inventory', ['page' => 'brands'])
            ->with('status', 'Brand deleted.');
    }
    public function editCategory(Request $request, int $category)
    {
        $this->authorizePos($request, 'pos.products.update');

        $record = DB::table('su_categories')->where('id', $category)->first();
        abort_unless($record, 404);

        // Exclude the category itself from the parent picker so it cannot
        // become its own parent.
        $parents = DB::table('su_categories')
            ->where('id', '!=', $category)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->view($request, 'categories', 'manage.inventory.categories.form', [
            'category' => $record,
            'parents'  => $parents,
        ]);
    }

    public function updateCategory(Request $request, int $category)
    {
        $this->authorizePos($request, 'pos.products.update');
        abort_unless(DB::table('su_categories')->where('id', $category)->exists(), 404);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'parent_id'   => ['nullable', 'integer', 'exists:su_categories,id'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if (isset($data['parent_id']) && (int) $data['parent_id'] === $category) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        if ($request->hasFile('image')) {
            $media = app(UploadService::class)->store($request->file('image'), 'categories');
            $data['image_path'] = $media->file_path;
        }
        unset($data['image']);

        DB::table('su_categories')->where('id', $category)->update($data);

        return redirect()
            ->route('manage.workspace.inventory', ['page' => 'categories'])
            ->with('status', 'Category updated.');
    }

    public function destroyCategory(Request $request, int $category)
    {
        $this->authorizePos($request, 'pos.products.delete');
        abort_unless(DB::table('su_categories')->where('id', $category)->exists(), 404);

        $usedBy = DB::table('su_products')->where('category_id', $category)->count();
        if ($usedBy > 0) {
            return back()->withErrors([
                'category' => 'This category is still linked to ' . $usedBy . ' product(s). Reassign them first.',
            ]);
        }

        DB::table('su_categories')->where('id', $category)->delete();

        return redirect()
            ->route('manage.workspace.inventory', ['page' => 'categories'])
            ->with('status', 'Category deleted.');
    }

    public function alerts(Request $request)
    {
        $this->authorizePos($request);
        
        $lowStockCount = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')
            ->groupBy('p.id')
            ->havingRaw('COALESCE(SUM(b.quantity),0) > 0 AND COALESCE(SUM(b.quantity),0) <= 5')
            ->get()->count();

        $outOfStockCount = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')
            ->groupBy('p.id')
            ->havingRaw('COALESCE(SUM(b.quantity),0) = 0')
            ->get()->count();

        $expiresSoonCount = DB::table('su_stock_batches')
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', today())
            ->where('expiry_date', '<=', today()->addDays(30))
            ->count();

        $expiredCount = DB::table('su_stock_batches')
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', today())
            ->count();

        $allCount = $lowStockCount + $outOfStockCount + $expiredCount;

        $tab = $request->query('tab', 'all');
        $search = trim((string) $request->query('q', ''));

        $query = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id', 'p.name')
            ->selectRaw('COALESCE(SUM(b.quantity),0) as stock')
            ->groupBy('p.id', 'p.name');

        if ($search !== '') {
            $query->where('p.name', 'like', '%' . addcslashes($search, '%_\\') . '%');
        }

        if ($tab === 'low') {
            $query->havingRaw('COALESCE(SUM(b.quantity),0) > 0 AND COALESCE(SUM(b.quantity),0) <= 5');
        } elseif ($tab === 'out') {
            $query->havingRaw('COALESCE(SUM(b.quantity),0) = 0');
        } elseif ($tab === 'expires') {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('su_stock_batches')
                    ->whereColumn('product_id', 'p.id')
                    ->where('quantity', '>', 0)
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', today())
                    ->where('expiry_date', '<=', today()->addDays(30));
            });
        } elseif ($tab === 'expired') {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('su_stock_batches')
                    ->whereColumn('product_id', 'p.id')
                    ->where('quantity', '>', 0)
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', today());
            });
        } elseif ($tab === 'all') {
            $query->havingRaw('COALESCE(SUM(b.quantity),0) <= 5')
                  ->orHavingRaw('EXISTS (SELECT 1 FROM su_stock_batches WHERE product_id = p.id AND quantity > 0 AND expiry_date IS NOT NULL AND expiry_date < CURRENT_DATE)');
        }

        $records = $query->orderBy('p.name')->paginate(20)->withQueryString();

        return $this->view($request, 'alerts', 'manage.inventory.alerts', compact(
            'records', 'search', 'tab', 'lowStockCount', 'outOfStockCount', 'expiresSoonCount', 'expiredCount', 'allCount'
        ));
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
