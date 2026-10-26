@extends('manage.layouts.workspace')
@section('title', 'Shifts')
@section('heading', 'Shifts')

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

    <!-- Closed Shifts List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Closed shifts</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        
        <div class="space-y-4">
            @forelse($closedShifts as $shift)
                <div class="flex justify-between items-center p-3 rounded-xl hover:bg-gray-50 transition border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-8 flex justify-center text-red-500">
                            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $shift->first_name }} {{ $shift->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($shift->closed_at)->format('Y-m-d H:i:s.u') }}</p>
                        </div>
                    </div>
                    <p class="font-semibold text-red-500">Cash {{ number_format($shift->closing_cash_counted - $shift->opening_cash, 0) }}</p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-6">No closed shifts found for this period.</p>
            @endforelse
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="flex gap-3">
        <button class="px-5 py-2 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition bg-white">
            <i class="fa-regular fa-file-pdf text-[#713FCE]"></i> Export PDF
        </button>
        <button class="px-5 py-2 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition bg-white">
            <i class="fa-solid fa-share-nodes text-[#713FCE]"></i> Share PDF
        </button>
    </div>
</div>
@endsection