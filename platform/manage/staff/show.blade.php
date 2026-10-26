@extends('manage.layouts.workspace')
@section('title', 'Staff details')
@section('heading', $staff->first_name . ' ' . $staff->last_name)
@section('subtitle', $staff->role)
@section('mobile_heading', 'Staff details')

@section('topbar_actions')
    <div class="hidden md:flex items-center gap-3">
        @if($staff->is_active)
            <form method="POST" action="{{ route('manage.staff.deactivate', $staff->id) }}" onsubmit="return confirm('Deactivate this staff member?')" class="m-0">
                @csrf @method('PATCH')
                <button type="submit" class="border border-gray-400 text-red-500 hover:bg-red-50 px-5 py-2 rounded-full font-medium flex items-center gap-2 transition">
                    <i class="fa-solid fa-ban"></i> Deactivate
                </button>
            </form>
        @endif
        <a href="{{ route('manage.staff.edit', $staff->id) }}" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-5 py-2 rounded-full font-medium flex items-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-pencil"></i> Edit
        </a>
    </div>
@endsection

@section('content')
<div class="swi-wrap max-w-4xl mx-auto space-y-6 pb-20 md:pb-0">
    
    <!-- Profile Header (Mobile & Desktop) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $staff->first_name }} {{ $staff->last_name }}</h2>
            <p class="text-gray-500">{{ $staff->role }}</p>
            <p class="text-sm mt-1 {{ $staff->is_active ? 'text-green-600' : 'text-red-500' }}">
                {{ $staff->is_active ? 'Active staff member' : 'Inactive' }}
            </p>
        </div>
        
        <!-- Mobile Actions -->
        <div class="flex gap-3 w-full md:hidden">
            <a href="{{ route('manage.staff.edit', $staff->id) }}" class="flex-1 bg-[#713FCE] text-white px-4 py-2.5 rounded-full font-medium text-center flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-pencil"></i> Edit staff
            </a>
            @if($staff->is_active)
            <form method="POST" action="{{ route('manage.staff.deactivate', $staff->id) }}" class="flex-1 m-0" onsubmit="return confirm('Deactivate this staff member?')">
                @csrf @method('PATCH')
                <button type="submit" class="w-full border border-gray-400 text-red-500 hover:bg-red-50 px-4 py-2.5 rounded-full font-medium text-center flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-ban"></i> Deactivate
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Sales Performance -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Sales performance</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-5 bg-white border border-[#e4dafa] rounded-xl">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">This week</span>
                        <span class="font-bold text-3xl text-gray-900">{{ number_format($salesThisWeek) }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center p-5 bg-white border border-[#e4dafa] rounded-xl">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">This month</span>
                        <span class="font-bold text-3xl text-gray-900">{{ number_format($salesThisMonth) }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center p-5 bg-white border border-[#e4dafa] rounded-xl">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sales this month</span>
                        <span class="font-bold text-3xl text-gray-900">{{ number_format($salesCount) }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-[#e3f6ef] text-green-600 flex items-center justify-center">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent sales</h3>
            <!-- Horizontal scroll on mobile, grid on desktop -->
            <div class="flex md:grid md:grid-cols-2 gap-4 overflow-x-auto pb-4 md:pb-0 snap-x">
                @forelse($recentSales as $sale)
                    <div class="min-w-[140px] snap-start border border-[#e4dafa] p-4 rounded-xl flex flex-col bg-white">
                        <i class="fa-solid fa-receipt text-[#713FCE] mb-3 text-xl"></i>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($sale->completed_at)->format('d/m/Y') }}</span>
                        <span class="font-bold text-lg text-gray-900 mt-1">{{ number_format($sale->total) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">No sales recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Sessions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Sessions</h3>
            <p class="text-xs text-gray-500 mb-6">Most recently opened first</p>
            <div class="space-y-6">
                @forelse($sessions as $session)
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            @if(!$session->is_closed)
                                <i class="fa-regular fa-circle-play text-[#713FCE] mt-1 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Open session</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->opened_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            @else
                                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-1 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Closed session</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->opened_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                        @if($session->is_closed)
                            <span class="text-sm text-red-500 whitespace-nowrap">Cash {{ number_format($session->closing_cash_counted - $session->opening_cash, 0) }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">No sessions recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Leave -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Leave</h3>
            <p class="text-xs text-gray-500 mb-6">Most recent first</p>
            
            <button onclick="document.getElementById('leave-form').classList.toggle('hidden')" class="w-full border border-gray-400 text-[#713FCE] font-medium py-3 rounded-full flex items-center justify-center gap-2 hover:bg-gray-50 transition mb-6">
                <i class="fa-regular fa-calendar-plus"></i> Log leave
            </button>

            <form id="leave-form" method="POST" action="{{ route('manage.staff.leave', $staff->id) }}" class="hidden bg-[#f4effc] p-5 rounded-xl mb-6 space-y-4">
                @csrf
                <div>
                    <label class="text-xs text-gray-600 font-medium">Start Date</label>
                    <input type="date" name="start_date" required class="w-full bg-transparent border-0 border-b border-gray-400 focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-sm outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-600 font-medium">End Date</label>
                    <input type="date" name="end_date" required class="w-full bg-transparent border-0 border-b border-gray-400 focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-sm outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-600 font-medium">Reason (Optional)</label>
                    <input type="text" name="reason" class="w-full bg-transparent border-0 border-b border-gray-400 focus:border-[#713FCE] focus:ring-0 px-0 py-1 text-sm outline-none">
                </div>
                <button type="submit" class="w-full bg-[#713FCE] text-white py-2.5 rounded-full text-sm font-medium">Save Leave</button>
            </form>

            <div class="space-y-4">
                @forelse($leaves as $leave)
                    <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-3">
                        <span class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</span>
                        <span class="text-gray-500 text-xs">{{ $leave->reason }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No leave logged yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection