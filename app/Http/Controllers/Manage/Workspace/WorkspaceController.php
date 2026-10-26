<?php

namespace App\Http\Controllers\Manage\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkspaceController extends Controller
{
    private function safeCount(string $table): int { return Schema::hasTable($table) ? DB::table($table)->count() : 0; }
    private function safeRows(string $table, int $limit=30) {
        return Schema::hasTable($table) ? DB::table($table)->orderByDesc('id')->limit($limit)->get() : collect();
    }
    
    public function home()
    {
        $hasSales = Schema::hasTable('su_sales');
        $today = today();
        $yesterday = today()->subDay();
        $last7Days = collect(range(6, 0))->map(fn($i) => today()->subDays($i));

        // 1. Top Metrics
        $todaySales = $hasSales ? (float) DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $today)->sum('total') : 0;
        $yesterdaySales = $hasSales ? (float) DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $yesterday)->sum('total') : 0;
        $salesChange = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : ($todaySales > 0 ? 100 : 0);

        $todayTx = $hasSales ? DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $today)->count() : 0;
        $yesterdayTx = $hasSales ? DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $yesterday)->count() : 0;
        $txChange = $yesterdayTx > 0 ? (($todayTx - $yesterdayTx) / $yesterdayTx) * 100 : ($todayTx > 0 ? 100 : 0);

        $lowStockCount = Schema::hasTable('su_stock_batches') ? DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')->groupBy('p.id')
            ->havingRaw('COALESCE(SUM(b.quantity),0) > 0 AND COALESCE(SUM(b.quantity),0) <= 5')
            ->get()->count() : 0;

        $owingCount = Schema::hasTable('su_customers') ? DB::table('su_customers')->where('credit_balance', '>', 0)->count() : 0;
        $owingTotal = Schema::hasTable('su_customers') ? (float) DB::table('su_customers')->sum('credit_balance') : 0;

        // 2. Sales Trend (Last 7 days)
        $trendData = [];
        $trendLabels = [];
        foreach ($last7Days as $date) {
            $val = $hasSales ? (float) DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $date)->sum('total') : 0;
            $trendData[] = $val;
            $trendLabels[] = $date->format('d/m');
        }
        $trendTotal = array_sum($trendData);
        $trendAvg = count($trendData) > 0 ? $trendTotal / count($trendData) : 0;
        $trendBest = max($trendData ?: [0]);

        // 3. Payment Mix (Last 7 days)
        $paymentMix = collect();
        $paymentTotal = 0;
        if ($hasSales && Schema::hasTable('su_payment_methods')) {
            $paymentMix = DB::table('su_payment_methods as pm')
                ->join('su_sales as s', 's.id', '=', 'pm.sale_id')
                ->where('s.status', 'completed')
                ->whereBetween('s.completed_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])
                ->select('pm.method', DB::raw('SUM(pm.amount) as total'))
                ->groupBy('pm.method')
                ->get();
            $paymentTotal = $paymentMix->sum('total');
        }

        // 4. Popular Products (Last 7 days)
        $popularProducts = collect();
        if ($hasSales && Schema::hasTable('su_sale_items')) {
            $popularProducts = DB::table('su_sale_items as i')
                ->join('su_sales as s', 's.id', '=', 'i.sale_id')
                ->join('su_products as p', 'p.id', '=', 'i.product_id')
                ->where('s.status', 'completed')
                ->whereBetween('s.completed_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])
                ->select('p.name', DB::raw('SUM(i.quantity) as units_sold'), DB::raw('SUM(i.line_total) as revenue'))
                ->groupBy('p.id', 'p.name')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get();
        }

        // 5. Needs Attention (Top owing customers)
        $needsAttention = collect();
        if (Schema::hasTable('su_customers')) {
            $needsAttention = DB::table('su_customers')
                ->where('credit_balance', '>', 0)
                ->orderByDesc('credit_balance')
                ->limit(5)
                ->get(['id', 'first_name', 'last_name', 'credit_balance']);
        }

        $modules = config('suweka_ui');
        $module = 'dashboard';
        $page = 'index';
        $item = collect($modules[$module]['items'] ?? [])->first(fn($i) => $i[0] === $page) ?? ['index', 'Dashboard', 'home'];

        return view('manage.dashboard.index', compact(
            'todaySales', 'salesChange', 'todayTx', 'txChange', 
            'lowStockCount', 'owingCount', 'owingTotal',
            'trendLabels', 'trendData', 'trendTotal', 'trendAvg', 'trendBest',
            'paymentMix', 'paymentTotal', 'popularProducts', 'needsAttention',
            'modules', 'module', 'page', 'item'
        ));
    }

    public function salesTrends()
    {
        $hasSales = Schema::hasTable('su_sales');
        $last7Days = collect(range(6, 0))->map(fn($i) => today()->subDays($i));

        // 1. Sales Trend (Last 7 days)
        $trendData = [];
        $trendLabels = [];
        $byDay = [];
        foreach ($last7Days as $date) {
            $val = $hasSales ? (float) DB::table('su_sales')->where('status', 'completed')->whereDate('completed_at', $date)->sum('total') : 0;
            $trendData[] = $val;
            $trendLabels[] = $date->format('d/m');
            $byDay[] = [
                'date' => $date->format('d/m/Y'),
                'total' => $val
            ];
        }
        $trendTotal = array_sum($trendData);
        $trendAvg = count($trendData) > 0 ? $trendTotal / count($trendData) : 0;
        $trendBest = max($trendData ?: [0]);

        // 2. Payment Mix (Last 7 days)
        $paymentMix = collect();
        $paymentTotal = 0;
        if ($hasSales && Schema::hasTable('su_payment_methods')) {
            $paymentMix = DB::table('su_payment_methods as pm')
                ->join('su_sales as s', 's.id', '=', 'pm.sale_id')
                ->where('s.status', 'completed')
                ->whereBetween('s.completed_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])
                ->select('pm.method', DB::raw('SUM(pm.amount) as total'))
                ->groupBy('pm.method')
                ->get();
            $paymentTotal = $paymentMix->sum('total');
        }

        // 3. Popular Products (Last 7 days)
        $popularProducts = collect();
        if ($hasSales && Schema::hasTable('su_sale_items')) {
            $popularProducts = DB::table('su_sale_items as i')
                ->join('su_sales as s', 's.id', '=', 'i.sale_id')
                ->join('su_products as p', 'p.id', '=', 'i.product_id')
                ->where('s.status', 'completed')
                ->whereBetween('s.completed_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])
                ->select('p.name', DB::raw('SUM(i.quantity) as units_sold'), DB::raw('SUM(i.line_total) as revenue'))
                ->groupBy('p.id', 'p.name')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get();
        }

        $modules = config('suweka_ui');
        $module = 'dashboard';
        $page = 'sales-trends';
        $item = collect($modules[$module]['items'] ?? [])->first(fn($i) => $i[0] === $page) ?? ['sales-trends', 'Sales Trends', 'chart-line'];

        return view('manage.dashboard.sales-trends', compact(
            'trendLabels', 'trendData', 'trendTotal', 'trendAvg', 'trendBest',
            'paymentMix', 'paymentTotal', 'popularProducts', 'byDay',
            'modules', 'module', 'page', 'item'
        ));
    }

    // ---------------
    // Main menu: module cards, no active module so the layout renders in home mode
    // ---------------
    public function menu()
    {
        $modules = config('suweka_ui');
        $counts = [
            'inventory' => $this->safeCount('su_products'),
            'customers' => $this->safeCount('su_customers'),
            'suppliers' => $this->safeCount('su_suppliers'),
            'staff'     => $this->safeCount('su_staff'),
        ];

        return view('manage.workspace.home', compact('modules', 'counts'));
    }

    public function show(Request $request, string $page='index') 
    {
        $module = explode('.', (string)$request->route()->getName())[2] ?? ''; 
        
        // Intercept the dashboard routes
        if ($module === 'dashboard') {
            if ($page === 'index' || $page === null) {
                return $this->home();
            }
            if (in_array($page, ['sales-trends', 'trends'], true)) {
                return $this->salesTrends();
            }
            if (in_array($page, ['stock-alerts', 'alerts'], true)) {
                return $this->stockAlerts($request);
            }
            if (in_array($page, ['customers-owing', 'owing', 'ledger'], true)) {
                return $this->customersOwing($request);
            }
        }

        $modules = config('suweka_ui');
        abort_unless(isset($modules[$module]),404);
        
        $item = collect($modules[$module]['items'])->first(fn($i)=>$i[0]===$page);
        abort_unless($item!==null,404);
        
        $records = collect(); 
        $stats = []; 
        $plot = [];
        
        $search = trim((string)$request->query('q', ''));
        $search = mb_substr($search, 0, 120);
        
        if (($module==='inventory' && $page==='products') || ($module==='customers' && $page==='index') || ($module==='suppliers' && $page==='index')) {
            $table = match($module) { 'inventory'=>'su_products', 'customers'=>'su_customers', default=>'su_suppliers' };
            $query=DB::table($table);
            if ($search !== '') {
                $escaped = addcslashes($search, '%_\\');
                $like = '%'.$escaped.'%';
                if ($module==='inventory') $query->where('name', 'like', $like);
                else $query->where(function($q) use ($like) {
                    $q->where('first_name','like',$like)->orWhere('last_name','like',$like)->orWhere('phone','like',$like);
                });
            }
            $records=$query->orderBy('id')->paginate(20)->withQueryString();
            return view('manage.workspace.page',compact('modules','module','page','item','records','stats','plot','search'));
        }
        
        if($module==='inventory' && in_array($page,['products','alerts','stocktake','create','categories','brands','backorders'])) {
            $table=match($page){'categories'=>'su_categories','brands'=>'su_brands','backorders'=>'su_backorders',default=>'su_products'};
            $records=$this->safeRows($table,100);
        } elseif($module==='sale') {
            $records=$this->safeRows($page==='new'?'su_products':'su_sales',100);
        } elseif($module==='customers') { 
            $records=$this->safeRows($page==='ledger'?'su_credit_transactions':'su_customers',100); 
        } elseif($module==='suppliers') { 
            $records=$this->safeRows(in_array($page,['orders','deliveries'])?'su_purchase_orders':'su_suppliers',100); 
        } elseif($module==='staff') { 
            $records=$this->safeRows('su_staff',100); 
        } elseif($module==='documents') { 
            $records=$this->safeRows('su_documents',100); 
        } elseif($module==='cashbox') { 
            $records=$this->safeRows($page==='index'?'su_cash_box':($page==='shifts'?'su_shifts':'su_cash_box'),100); 
        }
        
        if(in_array($module,['reports','cashbox'])) {
            $stats=['sales'=>0,'transactions'=>0,'low_stock'=>0,'owing'=>0,'products'=>0,'staff'=>0,'shifts'=>0,'vat'=>0];
            if(Schema::hasTable('su_sales')) {
                $sales=DB::table('su_sales')->where('status','completed');
                $stats['sales']=(float)(clone $sales)->whereDate('completed_at',today())->sum('total');
                $stats['transactions']=(clone $sales)->whereDate('completed_at',today())->count();
                for($i=6;$i>=0;$i--) {
                    $date=today()->subDays($i);
                    $plot[]=['day'=>$date->format('d/m'),'value'=>(float)DB::table('su_sales')->where('status','completed')->whereDate('completed_at',$date)->sum('total')];
                }
                $stats['vat']=(float)(clone $sales)->sum('tax_total');
            }
            if(Schema::hasTable('su_products')) $stats['products']=$this->safeCount('su_products');
            if(Schema::hasTable('su_staff')) $stats['staff']=$this->safeCount('su_staff');
            if(Schema::hasTable('su_shifts')) $stats['shifts']=$this->safeCount('su_shifts');
            if(Schema::hasTable('su_customers')) $stats['owing']=(float)DB::table('su_customers')->sum('credit_balance');
            if(Schema::hasTable('su_stock_batches') && Schema::hasColumn('su_stock_batches','quantity_remaining')) $stats['low_stock']=DB::table('su_stock_batches')->where('quantity_remaining','<=',5)->count();
        }
        
        return view('manage.workspace.page',compact('modules','module','page','item','records','stats','plot'));
    }

        public function stockAlerts(Request $request)
    {
        // 1. Fetch data (copied from InventoryWorkspaceController)
        $lowStockCount = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')->groupBy('p.id')
            ->havingRaw('COALESCE(SUM(b.quantity),0) > 0 AND COALESCE(SUM(b.quantity),0) <= 5')
            ->get()->count();

        $outOfStockCount = DB::table('su_products as p')
            ->leftJoin('su_stock_batches as b', 'b.product_id', '=', 'p.id')
            ->select('p.id')->groupBy('p.id')
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

        // 2. Force the layout to stay on Dashboard
        $modules = config('suweka_ui');
        $page = 'stock-alerts'; // Ensure this matches the slug in your config/suweka_ui.php
        $item = collect($modules['dashboard']['items'] ?? [])->first(fn ($i) => $i[0] === $page) ?? [$page, 'Stock Alerts', 'exclamation-triangle'];

        return view('manage.inventory.alerts', compact(
            'records', 'search', 'tab', 'lowStockCount', 'outOfStockCount', 'expiresSoonCount', 'expiredCount', 'allCount'
        ) + [
            'modules' => $modules,
            'module' => 'dashboard', // Keeps the sidebar in the Dashboard module!
            'page' => $page,
            'item' => $item,
            'alertsCount' => $allCount
        ]);
    }

    public function customersOwing(Request $request)
    {
        // 1. Fetch data (copied from CreditLedgerController)
        $inputs = $request->validate([
            'q'       => 'nullable|string|max:100',
            'balance' => 'nullable|in:all,over_limit',
            'sort'    => 'nullable|in:highest,lowest,name',
        ]);
        $search  = trim((string) ($inputs['q'] ?? ''));
        $balance = $inputs['balance'] ?? 'all';
        $sort    = $inputs['sort'] ?? 'highest';

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

        // 2. Force the layout to stay on Dashboard
        $modules = config('suweka_ui');
        $page = 'customers-owing'; // Ensure this matches the slug in your config/suweka_ui.php
        $item = collect($modules['dashboard']['items'] ?? [])->first(fn ($i) => $i[0] === $page) ?? [$page, 'Customers Owing', 'wallet'];

        return view('manage.customers.ledger', [
            'modules'    => $modules,
            'module'     => 'dashboard', // Keeps the sidebar in the Dashboard module!
            'page'       => $page,
            'item'       => $item,
            'records'    => $records,
            'owingCount' => $owingCount,
            'owingTotal' => $owingTotal,
            'search'     => $search,
            'balance'    => $balance,
            'sort'       => $sort,
        ]);
    }
}