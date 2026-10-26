@extends('manage.layouts.workspace')
@section('title', 'Performance')
@section('heading', 'Performance')
@section('mobile_heading', 'Performance')

@section('content')
<div class="swi-wrap max-w-5xl mx-auto space-y-4 pb-20 md:pb-6">

    <!-- Top Performer -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Top Performer</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">
                {{ $topPerformer ? $topPerformer->first_name . ' ' . $topPerformer->last_name : 'None yet' }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-trophy"></i>
        </div>
    </div>

    <!-- Their Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Their Revenue</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">
                {{ number_format($topRevenue, 0) }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-purple-50 text-[#713FCE] flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
    </div>

    <!-- Staff With Sales -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Staff With Sales</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">
                {{ $staffWithSales }}
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-user-group"></i>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Leaderboard</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>

        <div class="space-y-4">
            @forelse($leaderboard as $index => $entry)
                @php $staff = $entry->staff; @endphp
                <div class="flex items-center justify-between p-2 md:p-3 rounded-lg bg-gray-50/50 md:bg-transparent hover:bg-gray-50 transition border border-transparent md:border-gray-100">
                    <div class="flex items-center gap-4">
                        <!-- Rank Badge -->
                        <div class="w-8 h-8 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <!-- Staff Info -->
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $staff ? $staff->first_name . ' ' . $staff->last_name : 'Unknown Staff' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $entry->sales_count }} sale(s)
                            </p>
                        </div>
                    </div>
                    <!-- Revenue -->
                    <p class="font-semibold text-gray-900">
                        {{ number_format($entry->revenue, 0) }}
                    </p>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    No sales data for this month.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection