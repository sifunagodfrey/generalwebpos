@extends('manage.layouts.workspace')
@section('title', 'Profit & Loss')
@section('heading', 'Profit & Loss')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <!-- Filter -->
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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($revenue, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cost of Goods Sold</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-file-invoice"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($cogs, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Expenses</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-arrow-trend-down"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($expenses, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Net Profit</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-arrow-trend-up"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($netProfit, 0) }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Revenue, cost of goods and expenses</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        <div class="flex gap-6 mb-6">
            <div><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-900">{{ number_format($revenue + $cogs + $expenses, 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Daily Average</p><p class="text-2xl font-bold text-gray-900">{{ number_format(($revenue + $cogs + $expenses) / max(now()->daysInMonth, 1), 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Best Day</p><p class="text-2xl font-bold text-gray-900">{{ number_format(max($revenue, $cogs, $expenses), 0) }}</p></div>
        </div>
        <div class="h-72"><canvas id="plChart"></canvas></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initPlChart() {
        const canvas = document.getElementById('plChart');
        if (!canvas || typeof Chart === 'undefined') return;
        if(window.plChartInstance) window.plChartInstance.destroy();

        window.plChartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels: ['Revenue', 'COGS', 'Expenses'],
                datasets: [{
                    data: [{{ $revenue }}, {{ $cogs }}, {{ $expenses }}],
                    borderColor: '#713FCE',
                    backgroundColor: 'rgba(113, 63, 206, 0.1)',
                    fill: true,
                    tension: 0.1,
                    pointRadius: 4,
                    pointBackgroundColor: '#713FCE'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#697c9a' } },
                    y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initPlChart);
    document.addEventListener('turbo:load', initPlChart);
</script>
@endpush