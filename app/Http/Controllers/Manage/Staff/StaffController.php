<?php

namespace App\Http\Controllers\Manage\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Staff;
use App\Models\Staff\StaffLeave;
use App\Models\Cashbox\Shift;
use App\Models\Sales\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    private function layout(string $page, array $data = []): array
    {
        $modules = config('suweka_ui');
        return $data + [
            'modules' => $modules,
            'module' => 'staff',
            'page' => $page,
            'item' => collect($modules['staff']['items'] ?? [])->first(fn($i) => $i[0] === $page) ?? ['index', 'Staff'],
        ];
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        
        $staff = Staff::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('manage.staff.index', $this->layout('index', compact('staff', 'search')));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'role'       => 'required|string|in:Admin,Cashier,Manager',
            'password'   => 'required|string|min:8|max:16|confirmed',
        ]);

        DB::transaction(function () use ($data) {
            // Generate a unique dummy email for the user account
            $baseEmail = strtolower(Str::slug($data['first_name'] . '.' . $data['last_name'])) . '@pos.local';
            $email = $baseEmail;
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = strtolower(Str::slug($data['first_name'] . '.' . $data['last_name'])) . $counter++ . '@pos.local';
            }

            // Create User for authentication
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $email,
                'password'   => Hash::make($data['password']),
                'status'     => 'active',
            ]);

            // Create Staff record for POS operations
            Staff::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'role'       => $data['role'],
                'pin_hash'   => Hash::make($data['password']),
                'is_active'  => 1,
                'user_id'    => $user->id,
            ]);
        });

        return redirect()->route('manage.workspace.staff', ['page' => 'index'])->with('status', 'Staff member added successfully.');
    }

    public function show(Staff $staff)
    {
        $salesThisWeek = Sale::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total');

        $salesThisMonth = Sale::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->sum('total');

        $salesCount = Sale::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->count();

        $recentSales = Sale::where('staff_id', $staff->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->limit(6)
            ->get();

        $sessions = Shift::where('staff_id', $staff->id)
            ->latest('opened_at')
            ->limit(5)
            ->get();

        $leaves = StaffLeave::where('staff_id', $staff->id)
            ->latest('start_date')
            ->limit(5)
            ->get();

        return view('manage.staff.show', $this->layout('show', compact(
            'staff', 'salesThisWeek', 'salesThisMonth', 'salesCount', 'recentSales', 'sessions', 'leaves'
        )));
    }

    public function edit(Staff $staff)
    {
        return view('manage.staff.edit', $this->layout('edit', compact('staff')));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'role'       => 'required|string|in:Admin,Cashier,Manager',
            'password'   => 'nullable|string|min:8|max:16|confirmed',
        ]);

        DB::transaction(function () use ($data, $staff) {
            $staff->update([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'role'       => $data['role'],
            ]);

            if ($staff->user) {
                $staff->user->update([
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                ]);

                if (!empty($data['password'])) {
                    $staff->user->update(['password' => Hash::make($data['password'])]);
                    $staff->update(['pin_hash' => Hash::make($data['password'])]);
                }
            }
        });

        return redirect()->route('manage.staff.show', $staff->id)->with('status', 'Staff member updated.');
    }

    public function deactivate(Staff $staff)
    {
        $staff->update(['is_active' => 0]);
        if ($staff->user) {
            $staff->user->update(['status' => 'suspended']);
        }
        return redirect()->route('manage.workspace.staff', ['page' => 'index'])->with('status', 'Staff member deactivated.');
    }

    public function logLeave(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:255',
        ]);

        $loggedBy = Staff::where('user_id', $request->user()->id)->value('id');

        $staff->staffLeaveByStaffId()->create([
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'reason'     => $data['reason'] ?? 'Not specified',
            'logged_by_staff_id' => $loggedBy ?? $staff->id,
        ]);

        return back()->with('status', 'Leave logged successfully.');
    }

    private function getPerformanceMetrics()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $topPerformerData = Sale::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
            ->select('staff_id', DB::raw('SUM(total) as revenue'))
            ->groupBy('staff_id')
            ->orderByDesc('revenue')
            ->with('staff')
            ->first();

        $staffWithSales = Sale::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
            ->distinct('staff_id')
            ->count('staff_id');

        $leaderboard = Sale::where('status', 'completed')
            ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
            ->select('staff_id', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(id) as sales_count'))
            ->groupBy('staff_id')
            ->orderByDesc('revenue')
            ->with('staff')
            ->take(10)
            ->get();

        return [
            'topPerformer' => $topPerformerData ? $topPerformerData->staff : null,
            'topRevenue' => $topPerformerData ? (float) $topPerformerData->revenue : 0,
            'staffWithSales' => $staffWithSales,
            'leaderboard' => $leaderboard,
        ];
    }

    public function performance()
    {
        $metrics = $this->getPerformanceMetrics();
        return view('manage.staff.performance', $this->layout('performance', $metrics));
    }

    public function leave()
    {
        $staff = Staff::where('is_active', 1)->orderBy('first_name')->get();
        
        // Assuming you have a StaffLeave model relationship
        $leaves = \App\Models\Staff\StaffLeave::with(['staff', 'loggedByStaff'])
            ->latest('start_date')
            ->paginate(20);

        return view('manage.staff.leave', $this->layout('leave', compact('staff', 'leaves')));
    }

    public function storeLeave(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:su_staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        $loggedBy = Staff::where('user_id', $request->user()->id)->value('id');

        StaffLeave::create([
            'staff_id' => $data['staff_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'logged_by_staff_id' => $loggedBy,
        ]);

        return back()->with('status', 'Leave logged successfully.');
    }
    public function reports()
    {
        $metrics = $this->getPerformanceMetrics();
        
        return view('manage.staff.reports', $this->layout('reports', $metrics));
    }
}