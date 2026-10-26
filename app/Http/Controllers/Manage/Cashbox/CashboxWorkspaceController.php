<?php

namespace App\Http\Controllers\Manage\Cashbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashboxWorkspaceController extends Controller
{
    public function index(Request $request, string $page = 'index')
    {
        $modules = config('suweka_ui');
        $module = 'cashbox';
        $item = collect($modules[$module]['items'] ?? [])->first(fn ($v) => $v[0] === $page) ?? [$page, 'Cash Box', 'wallet'];

        $user = Auth::user();
        $staff = DB::table('su_staff')->where('user_id', $user->id)->first();
        $staffId = $staff ? $staff->id : null;

        $viewData = compact('modules', 'module', 'page', 'item');

        switch ($page) {
            case 'history':
                $viewData['shifts'] = $this->getShiftHistory($request, $staffId);
                return view('manage.cashbox.history', $viewData);
            
            case 'movement':
                $viewData['movements'] = $this->getCashMovements($request, $staffId, 'all');
                return view('manage.cashbox.movement', $viewData);

            case 'income':
                $viewData['movements'] = $this->getCashMovements($request, $staffId, 'in');
                return view('manage.cashbox.income', $viewData);

            case 'expenses':
                $viewData['movements'] = $this->getCashMovements($request, $staffId, 'out');
                return view('manage.cashbox.expenses', $viewData);

            case 'reports':
                // Get filter parameters
                $period = $request->input('period', 'today');
                $from = $request->input('from');
                $to = $request->input('to');
                
                $viewData['reports'] = $this->getReportsData($staffId, $period, $from, $to);
                $viewData['period'] = $period; // Pass back to view for active styling
                $viewData['from'] = $from;
                $viewData['to'] = $to;
                
                return view('manage.cashbox.reports', $viewData);

            default:
                $viewData = array_merge($viewData, $this->getDashboardData($staffId));
                return view('manage.cashbox.index', $viewData);
        }
    }

    private function getReportsData($staffId, $period, $from = null, $to = null)
    {
        // 1. Determine Date Range
        $start = null;
        $end = null;

        if ($period === 'today') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        } elseif ($period === 'week') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
        } elseif ($period === 'month') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
        } elseif ($period === 'custom' && $from && $to) {
            $start = Carbon::parse($from)->startOfDay();
            $end = Carbon::parse($to)->endOfDay();
        }

        // 2. Base Queries
        $queryPayments = DB::table('su_payment_methods')->where('staff_id', $staffId);
        
        $queryCashBox = DB::table('su_cash_box as cb')
            ->join('su_shifts as s', 's.id', '=', 'cb.shift_id')
            ->where('s.staff_id', $staffId);

        // Apply Date Filter to both
        if ($start && $end) {
            $queryPayments->whereBetween('created_at', [$start, $end]);
            $queryCashBox->whereBetween('cb.created_at', [$start, $end]);
        }

        // 3. Calculate Cash & Mpesa In (from Payments)
        $payments = $queryPayments->get();
        $cashIn = $payments->where('method', 'cash')->sum('amount');
        $mpesaIn = $payments->where('method', 'mpesa')->sum('amount');

        // 4. Calculate Cash & Mpesa Out (from Cash Box manual entries)
        $cashBoxEntries = $queryCashBox->get();
        $cashOut = $cashBoxEntries->where('direction', 'out')->where('type', 'cash')->sum('amount');
        $mpesaOut = $cashBoxEntries->where('direction', 'out')->where('type', 'mpesa')->sum('amount');

        // 5. Net Calculations
        $netCash = $cashIn - $cashOut;
        $netMpesa = $mpesaIn - $mpesaOut;

        // 6. Category Breakdowns (Fixed)
        
        // --- INCOME CATEGORIES ---
        $rawIncome = collect();

        // Add Sales as an income category
        if ($payments->sum('amount') > 0) {
            $rawIncome->push((object)[
                'name' => 'Sales',
                'total' => $payments->sum('amount')
            ]);
        }

        // Add Manual Cash In (using leftJoin to catch uncategorized items)
        $manualIncome = DB::table('su_cash_box as cb')
            ->join('su_shifts as s', 's.id', '=', 'cb.shift_id')
            ->leftJoin('su_cash_box_categories as cat', 'cat.id', '=', 'cb.category_id')
            ->where('s.staff_id', $staffId)
            ->where('cb.direction', 'in')
            ->when($start && $end, fn($q) => $q->whereBetween('cb.created_at', [$start, $end]))
            ->select(DB::raw("COALESCE(cat.name, 'Uncategorized') as name"), DB::raw('SUM(cb.amount) as total'))
            ->groupBy('name')
            ->get();

        foreach ($manualIncome as $item) {
            $rawIncome->push($item);
        }

        // Group by name to merge "Sales" with manual "Sales" if they exist
        $categoriesIn = $rawIncome->groupBy('name')->map(function ($group) {
            return (object)[
                'name' => $group->first()->name,
                'total' => $group->sum('total')
            ];
        })->values();

        // --- EXPENSE CATEGORIES ---
        $categoriesOut = DB::table('su_cash_box as cb')
            ->join('su_shifts as s', 's.id', '=', 'cb.shift_id')
            ->leftJoin('su_cash_box_categories as cat', 'cat.id', '=', 'cb.category_id')
            ->where('s.staff_id', $staffId)
            ->where('cb.direction', 'out')
            ->when($start && $end, fn($q) => $q->whereBetween('cb.created_at', [$start, $end]))
            ->select(DB::raw("COALESCE(cat.name, 'Uncategorized') as name"), DB::raw('SUM(cb.amount) as total'))
            ->groupBy('name')
            ->get();

        return [
            'cash_in' => $cashIn, 
            'cash_out' => $cashOut, 
            'mpesa_in' => $mpesaIn, 
            'mpesa_out' => $mpesaOut,
            'net_cash' => $netCash, 
            'net_mpesa' => $netMpesa, 
            'categories_in' => $categoriesIn, 
            'categories_out' => $categoriesOut
        ];
    }

    private function getDashboardData($staffId)
    {
        $shift = null;
        $recentActivity = collect();
        $moneyIn = 0; $moneyOut = 0; $cashOnHand = 0; $mpesaOnHand = 0;
        $openingFloat = 0; $netMovement = 0; $totalOnHand = 0;

        if ($staffId) {
            $shift = DB::table('su_shifts')->where('staff_id', $staffId)->where('is_closed', 0)->latest('id')->first();
            if ($shift) {
                $openingCash = (float) $shift->opening_cash;
                $openingMpesa = (float) $shift->opening_mobile_money;
                $openingFloat = $openingCash + $openingMpesa;

                $payments = DB::table('su_payment_methods')->where('shift_id', $shift->id)->get();
                $cashIn = $payments->where('method', 'cash')->sum('amount');
                $mpesaIn = $payments->where('method', 'mpesa')->sum('amount');
                $moneyIn = $cashIn + $mpesaIn;
                
                $cashMovements = DB::table('su_cash_box')->where('shift_id', $shift->id)->get();
                $cashOut = $cashMovements->where('direction', 'out')->sum('amount');
                $moneyOut = $cashOut;

                $cashOnHand = $openingCash + $cashIn - $cashMovements->where('direction', 'out')->where('type', 'cash')->sum('amount');
                $mpesaOnHand = $openingMpesa + $mpesaIn - $cashMovements->where('direction', 'out')->where('type', 'mpesa')->sum('amount');
                $netMovement = $moneyIn - $moneyOut;
                $totalOnHand = $cashOnHand + $mpesaOnHand;

                $activities = collect();
                foreach($payments as $p) {
                    $activities->push((object)[
                        'title' => 'Sale payment' . ($p->reference ? ' - ' . $p->reference : ''),
                        'subtitle' => ucfirst($p->method) . ' • ' . Carbon::parse($p->created_at)->format('d/m/Y H:i'),
                        'amount' => (float)$p->amount, 'is_in' => true, 'timestamp' => $p->created_at,
                        'direction' => 'Cash in', 'type' => ucfirst($p->method), 'date_full' => $p->created_at, 'sale_id' => $p->sale_id
                    ]);
                }
                foreach($cashMovements as $m) {
                    $isIn = strtolower($m->direction) === 'in';
                    $activities->push((object)[
                        'title' => $m->reason ?? ($isIn ? 'Money in' : 'Money out'),
                        'subtitle' => ucfirst($m->type ?? 'Cash') . ' • ' . Carbon::parse($m->created_at)->format('d/m/Y H:i'),
                        'amount' => (float)$m->amount, 'is_in' => $isIn, 'timestamp' => $m->created_at,
                        'direction' => $isIn ? 'Cash in' : 'Cash out', 'type' => ucfirst($m->type ?? 'Cash'), 'date_full' => $m->created_at, 'sale_id' => null
                    ]);
                }
                $recentActivity = $activities->sortByDesc('timestamp')->take(20);
            }
        }

        return compact('shift', 'recentActivity', 'moneyIn', 'moneyOut', 'cashOnHand', 'mpesaOnHand', 'openingFloat', 'netMovement', 'totalOnHand');
    }

    private function getShiftHistory(Request $request, $staffId)
    {
        $query = DB::table('su_shifts as s')
            ->leftJoin('su_staff as st', 'st.id', '=', 's.staff_id')
            ->select('s.*', 'st.first_name', 'st.last_name')
            ->orderByDesc('s.opened_at');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('s.id', 'like', "%{$search}%")
                  ->orWhere('st.first_name', 'like', "%{$search}%")
                  ->orWhere('st.last_name', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'open') $query->where('s.is_closed', 0);
        if ($request->input('status') === 'closed') $query->where('s.is_closed', 1);

        return $query->paginate(20)->withQueryString();
    }

    private function getCashMovements(Request $request, $staffId, $filter)
    {
        // 1. Get Payments
        $paymentsQuery = DB::table('su_payment_methods as pm')
            ->join('su_sales as sa', 'sa.id', '=', 'pm.sale_id')
            ->leftJoin('su_customers as c', 'c.id', '=', 'sa.customer_id')
            ->select('pm.*', 'pm.id as payment_id', 'c.first_name', 'c.last_name')
            ->where('pm.staff_id', $staffId);

        // 2. Get Cash Box Entries (FIXED: Join su_shifts to get staff_id)
        $cashQuery = DB::table('su_cash_box as cb')
            ->join('su_shifts as s', 's.id', '=', 'cb.shift_id')
            ->select('cb.*', 'cb.id as cash_id')
            ->where('s.staff_id', $staffId);

        $movements = collect();

        if ($filter === 'all' || $filter === 'in') {
            foreach ($paymentsQuery->get() as $p) {
                $name = trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? ''));
                $movements->push((object)[
                    'id' => 'p_'.$p->payment_id,
                    'title' => 'Sale payment' . ($name ? ' - '.$name : ''),
                    'subtitle' => ucfirst($p->method) . ' - ' . Carbon::parse($p->created_at)->format('d/m/Y'),
                    'amount' => (float)$p->amount, 'is_in' => true,
                    'direction' => 'Cash in', 'type' => ucfirst($p->method), 'date_full' => $p->created_at, 'sale_id' => $p->sale_id
                ]);
            }
        }

        // Merge manual cash box entries based on filter
        $manuals = $cashQuery->when($filter !== 'all', fn($q) => $q->where('cb.direction', $filter))->get();
        foreach ($manuals as $m) {
            $isIn = $m->direction === 'in';
            $movements->push((object)[
                'id' => 'c_'.$m->cash_id,
                'title' => $m->reason ?? ($isIn ? 'Manual Cash In' : 'Manual Cash Out'),
                'subtitle' => ucfirst($m->type ?? 'Cash') . ' - ' . Carbon::parse($m->created_at)->format('d/m/Y'),
                'amount' => (float)$m->amount, 'is_in' => $isIn,
                'direction' => $isIn ? 'Cash in' : 'Cash out', 'type' => ucfirst($m->type ?? 'Cash'), 'date_full' => $m->created_at, 'sale_id' => null
            ]);
        }

        return $movements->sortByDesc('date_full')->values();
    }

    public function storeIncome(Request $request)
    {
        return $this->storeMovement($request, 'in');
    }

    public function storeExpense(Request $request)
    {
        return $this->storeMovement($request, 'out');
    }

    private function storeMovement(Request $request, $direction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,mpesa',
            'reason' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer',
        ]);

        $staff = DB::table('su_staff')->where('user_id', Auth::id())->first();
        
        if (!$staff) {
            return back()->withErrors(['amount' => 'Your account is not linked to a staff record.']);
        }

        $shift = DB::table('su_shifts')->where('staff_id', $staff->id)->where('is_closed', 0)->latest('id')->first();

        if (!$shift) {
            return back()->withErrors(['amount' => 'You must have an open shift to record movements.']);
        }

        DB::table('su_cash_box')->insert([
            'shift_id'    => $shift->id,
            'direction'   => $direction, 
            'type'        => $request->method, 
            'amount'      => $request->amount,
            'category_id' => $request->category_id,
            'reason'      => $request->reason,
            'business_id' => $shift->business_id,
            'branch_id'   => $shift->branch_id,
            'created_at'  => now(),
        ]);

        $page = $direction === 'in' ? 'income' : 'expenses';

        return redirect()->route('manage.workspace.cashbox', ['page' => $page])
            ->with('success', 'Movement recorded successfully.');
    }
}