<?php

namespace App\Http\Controllers\Manage\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsWorkspaceController extends Controller
{
    public function index(Request $request, string $page = 'index')
    {
        $modules = config('suweka_ui');
        $module = 'reports';
        $item = collect($modules[$module]['items'] ?? [])->first(fn ($v) => $v[0] === $page) ?? [$page, 'Overview', 'chart-simple'];

        // Date Range (Defaulting to This Month as per screenshots)
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        
        // Previous Month for comparison
        $prevStart = now()->subMonthNoOverflow()->startOfMonth();
        $prevEnd = now()->subMonthNoOverflow()->endOfMonth();

        $viewData = compact('modules', 'module', 'page', 'item');

        switch ($page) {
            case 'sales':
                return view('manage.reports.sales', $viewData + $this->getSalesData($start, $end));
            case 'profit-loss':
                return view('manage.reports.profit-loss', $viewData + $this->getProfitLossData($start, $end, $prevStart, $prevEnd));
            case 'vat':
                return view('manage.reports.vat', $viewData + $this->getVatData($start, $end, $prevStart, $prevEnd));
            case 'products':
                return view('manage.reports.products', $viewData + $this->getProductsData($start, $end, $prevStart, $prevEnd));
            case 'staff':
                return view('manage.reports.staff', $viewData + $this->getStaffData($start, $end, $prevStart, $prevEnd));
            case 'shifts':
                return view('manage.reports.shifts', $viewData + $this->getShiftsData($start, $end, $prevStart, $prevEnd));
            default:
                return view('manage.reports.overview', $viewData + $this->getOverviewData($start, $end, $prevStart, $prevEnd));
        }
    }

    private function getOverviewData($start, $end, $prevStart, $prevEnd)
    {
        $sales = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end]);
        
        $totalSales = (float) (clone $sales)->sum('total');
        $totalVat = (float) (clone $sales)->sum('tax_total');
        $salesCount = (clone $sales)->count();
        $productsSold = DB::table('su_sale_items')->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->distinct('su_sale_items.product_id')->count('su_sale_items.product_id');
        $staffWithSales = (clone $sales)->distinct('staff_id')->count('staff_id');
        $shiftsStarted = DB::table('su_shifts')->whereBetween('opened_at', [$start, $end])->count();

        $prevTotal = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$prevStart, $prevEnd])->sum('total');
        $diff = $totalSales - $prevTotal;
        $bannerMessage = $diff > 0 ? "You sold " . number_format($diff, 0) . " more than last month." : ($diff < 0 ? "You sold " . number_format(abs($diff), 0) . " less than last month." : "You sold about the same as last month.");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        // Sales Trend
        $trendData = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')->orderBy('date')->get();
        
        $trendLabels = []; $trendValues = [];
        for ($i = 1; $i <= now()->daysInMonth; $i++) {
            $date = now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
            $trendLabels[] = $i . '/' . now()->month;
            $trendValues[] = (float) ($trendData->firstWhere('date', $date)->total ?? 0);
        }

        // Payment Mix
        $paymentMix = DB::table('su_payment_methods')
            ->join('su_sales', 'su_sales.id', '=', 'su_payment_methods.sale_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_payment_methods.method', DB::raw('SUM(su_payment_methods.amount) as total'))
            ->groupBy('su_payment_methods.method')->get();

        // Top Sellers
        $topSellers = DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->join('su_products', 'su_products.id', '=', 'su_sale_items.product_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_products.name', DB::raw('SUM(su_sale_items.quantity) as units_sold'), DB::raw('SUM(su_sale_items.line_total) as revenue'))
            ->groupBy('su_products.id', 'su_products.name')->orderByDesc('revenue')->limit(5)->get();

        return compact('totalSales', 'totalVat', 'salesCount', 'productsSold', 'staffWithSales', 'shiftsStarted', 'bannerMessage', 'bannerType', 'trendLabels', 'trendValues', 'paymentMix', 'topSellers');
    }

    private function getSalesData($start, $end)
    {
        $sales = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end]);
        $count = (clone $sales)->count();
        $subtotal = (float) (clone $sales)->sum('subtotal');
        $vat = (float) (clone $sales)->sum('tax_total');
        $total = (float) (clone $sales)->sum('total');

        $paymentMix = DB::table('su_payment_methods')
            ->join('su_sales', 'su_sales.id', '=', 'su_payment_methods.sale_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_payment_methods.method', DB::raw('SUM(su_payment_methods.amount) as total'))
            ->groupBy('su_payment_methods.method')->get();

        $trendData = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')->orderBy('date')->get();
        
        $trendLabels = []; $trendValues = [];
        for ($i = 1; $i <= now()->daysInMonth; $i++) {
            $date = now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
            $trendLabels[] = $i . '/' . now()->month;
            $trendValues[] = (float) ($trendData->firstWhere('date', $date)->total ?? 0);
        }

        $topProducts = DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->join('su_products', 'su_products.id', '=', 'su_sale_items.product_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_products.id', 'su_products.name', DB::raw('SUM(su_sale_items.quantity) as units_sold'), DB::raw('SUM(su_sale_items.line_total) as revenue'))
            ->groupBy('su_products.id', 'su_products.name')->orderByDesc('revenue')->limit(10)->get();

        return compact('count', 'subtotal', 'vat', 'total', 'paymentMix', 'trendLabels', 'trendValues', 'topProducts');
    }

    private function getProfitLossData($start, $end, $prevStart, $prevEnd)
    {
        $revenue = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end])->sum('total');
        
        $cogs = (float) DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->join('su_products', 'su_products.id', '=', 'su_sale_items.product_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->sum(DB::raw('su_sale_items.quantity * su_products.cost_price'));

        $expenses = (float) DB::table('su_cash_box')
            ->join('su_shifts', 'su_shifts.id', '=', 'su_cash_box.shift_id')
            ->where('su_cash_box.direction', 'out')->whereBetween('su_cash_box.created_at', [$start, $end])
            ->sum('su_cash_box.amount');

        $netProfit = $revenue - $cogs - $expenses;

        $prevRevenue = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$prevStart, $prevEnd])->sum('total');
        $prevCogs = (float) DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->join('su_products', 'su_products.id', '=', 'su_sale_items.product_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$prevStart, $prevEnd])
            ->sum(DB::raw('su_sale_items.quantity * su_products.cost_price'));
        $prevExpenses = (float) DB::table('su_cash_box')
            ->join('su_shifts', 'su_shifts.id', '=', 'su_cash_box.shift_id')
            ->where('su_cash_box.direction', 'out')->whereBetween('su_cash_box.created_at', [$prevStart, $prevEnd])
            ->sum('su_cash_box.amount');
        $prevNetProfit = $prevRevenue - $prevCogs - $prevExpenses;

        $diff = $netProfit - $prevNetProfit;
        $bannerMessage = $diff > 0 ? "You made in profit " . number_format($diff, 0) . " more than last month." : ($diff < 0 ? "You made in profit " . number_format(abs($diff), 0) . " less than last month." : "Your profit is about the same as last month.");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        return compact('revenue', 'cogs', 'expenses', 'netProfit', 'bannerMessage', 'bannerType');
    }

    private function getVatData($start, $end, $prevStart, $prevEnd)
    {
        $sales = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end]);
        $vatCollected = (float) (clone $sales)->sum('tax_total');
        $totalCollected = (float) (clone $sales)->sum('total');
        $taxableSales = $totalCollected - $vatCollected;
        $completedSales = (clone $sales)->count();

        $prevVat = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$prevStart, $prevEnd])->sum('tax_total');
        $diff = $vatCollected - $prevVat;
        $bannerMessage = $diff > 0 ? "You collected " . number_format($diff, 0) . " more in VAT than last month." : ($diff < 0 ? "You collected " . number_format(abs($diff), 0) . " less in VAT than last month." : "You collected in VAT about the same as last month (" . number_format($vatCollected, 0) . ").");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        $trendData = DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(tax_total) as total'))
            ->groupBy('date')->orderBy('date')->get();
        
        $trendLabels = []; $trendValues = [];
        for ($i = 1; $i <= now()->daysInMonth; $i++) {
            $date = now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
            $trendLabels[] = $i . '/' . now()->month;
            $trendValues[] = (float) ($trendData->firstWhere('date', $date)->total ?? 0);
        }

        return compact('vatCollected', 'taxableSales', 'completedSales', 'totalCollected', 'bannerMessage', 'bannerType', 'trendLabels', 'trendValues');
    }

    private function getProductsData($start, $end, $prevStart, $prevEnd)
    {
        $topProducts = DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->join('su_products', 'su_products.id', '=', 'su_sale_items.product_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_products.id', 'su_products.name', DB::raw('SUM(su_sale_items.quantity) as units_sold'), DB::raw('SUM(su_sale_items.line_total) as revenue'))
            ->groupBy('su_products.id', 'su_products.name')->orderByDesc('revenue')->limit(10)->get();

        $bestSeller = $topProducts->first();
        $unitsSold = $topProducts->sum('units_sold');
        $revenue = $topProducts->sum('revenue');
        $trackedProducts = DB::table('su_products')->where('is_active', 1)->count();

        $prevUnits = (float) DB::table('su_sale_items')
            ->join('su_sales', 'su_sales.id', '=', 'su_sale_items.sale_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$prevStart, $prevEnd])
            ->sum('su_sale_items.quantity');
        $diff = $unitsSold - $prevUnits;
        $bannerMessage = $diff > 0 ? "You moved " . number_format($diff, 0) . " more than last month." : ($diff < 0 ? "You moved " . number_format(abs($diff), 0) . " less than last month." : "You moved about the same as last month.");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        return compact('topProducts', 'bestSeller', 'unitsSold', 'revenue', 'trackedProducts', 'bannerMessage', 'bannerType');
    }

    private function getStaffData($start, $end, $prevStart, $prevEnd)
    {
        $leaderboard = DB::table('su_sales')
            ->join('su_staff', 'su_staff.id', '=', 'su_sales.staff_id')
            ->where('su_sales.status', 'completed')->whereBetween('su_sales.completed_at', [$start, $end])
            ->select('su_staff.first_name', 'su_staff.last_name', DB::raw('SUM(su_sales.total) as revenue'), DB::raw('COUNT(su_sales.id) as sales_count'))
            ->groupBy('su_staff.id', 'su_staff.first_name', 'su_staff.last_name')->orderByDesc('revenue')->get();

        $topPerformer = $leaderboard->first();
        $theirRevenue = $topPerformer ? $topPerformer->revenue : 0;
        $staffWithSales = $leaderboard->count();

        $prevTotal = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$prevStart, $prevEnd])->sum('total');
        $total = (float) DB::table('su_sales')->where('status', 'completed')->whereBetween('completed_at', [$start, $end])->sum('total');
        $diff = $total - $prevTotal;
        $bannerMessage = $diff > 0 ? "You sold as a team " . number_format($diff, 0) . " more than last month." : ($diff < 0 ? "You sold as a team " . number_format(abs($diff), 0) . " less than last month." : "Your team sold about the same as last month.");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        return compact('leaderboard', 'topPerformer', 'theirRevenue', 'staffWithSales', 'bannerMessage', 'bannerType');
    }

    private function getShiftsData($start, $end, $prevStart, $prevEnd)
    {
        $closedShifts = DB::table('su_shifts')
            ->join('su_staff', 'su_staff.id', '=', 'su_shifts.staff_id')
            ->where('su_shifts.is_closed', 1)->whereBetween('su_shifts.closed_at', [$start, $end])
            ->select('su_shifts.*', 'su_staff.first_name', 'su_staff.last_name')
            ->orderByDesc('su_shifts.closed_at')->get();

        $prevCount = DB::table('su_shifts')->where('is_closed', 1)->whereBetween('closed_at', [$prevStart, $prevEnd])->count();
        $count = $closedShifts->count();
        $diff = $count - $prevCount;
        $bannerMessage = $diff > 0 ? "You closed " . number_format($diff, 0) . " more than last month." : ($diff < 0 ? "You closed " . number_format(abs($diff), 0) . " less than last month." : "You closed the same amount as last month.");
        $bannerType = $diff > 0 ? 'success' : ($diff < 0 ? 'danger' : 'neutral');

        return compact('closedShifts', 'bannerMessage', 'bannerType');
    }
}