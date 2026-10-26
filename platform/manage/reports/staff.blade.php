@extends('manage.layouts.workspace')
@section('title', 'Staff')
@section('heading', 'Staff')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <div class="flex justify-end mb-6">
        <button class="px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm font-medium text-gray-700 flex items-center gap-2 hover:bg-gray-50 transition">
            <i class="fa-regular fa-calendar text-[#713FCE]"></i> This month <i class="fa-solid fa-chevron-down text-xs"></i>
        </button>
    </div>

    <!-- Banner -->
    <div class="p-4 rounded-xl mb-6 flex items-center gap-3 {{ $bannerType === 'success' ? 'bg-green-50 text-green-700' : ($bannerType === 'danger' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-700') }}">
        <i class="fa-solid {{ $bannerType === 'success' ? 'fa-arrow-trend-up' : ($bannerType === 'danger' ? 'fa-arrow-trend-down' : 'fa-arrow-right') }}"></i>
        <span class="font-medium">New activity • {{ $bannerMessage }}</span>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Top Performer</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-trophy"></i></div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $topPerformer ? $topPerformer->first_name . ' ' . $topPerformer->last_name : 'N/A' }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Their Revenue</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($theirRevenue, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff With Sales</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-users"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $staffWithSales }}</p>
        </div>
    </div>

    <!-- Leaderboard List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Leaderboard</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        <div class="space-y-4">
            @foreach($leaderboard as $index => $entry)
                <div class="flex justify-between items-center p-3 rounded-xl hover:bg-gray-50 transition border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $entry->first_name }} {{ $entry->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->sales_count }} sale(s)</p>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">{{ number_format($entry->revenue, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection