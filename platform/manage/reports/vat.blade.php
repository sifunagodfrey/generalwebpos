@extends('manage.layouts.workspace')
@section('title', 'VAT')
@section('heading', 'VAT')

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
        <span class="font-medium">No change • {{ $bannerMessage }}</span>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">VAT Collected</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($vatCollected, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Taxable Sales</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($taxableSales, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Completed Sales</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-receipt"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($completedSales, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Collected</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-cash-register"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCollected, 0) }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">VAT collected</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        <div class="flex gap-6 mb-6">
            <div><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-900">{{ number_format($vatCollected, 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Daily Average</p><p class="text-2xl font-bold text-gray-900">{{ number_format($vatCollected / max(now()->daysInMonth, 1), 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Best Day</p><p class="text-2xl font-bold text-gray-900">{{ number_format(max($trendValues), 0) }}</p></div>
        </div>
        <div class="h-64"><canvas id="vatChart"></canvas></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initVatChart() {
        const canvas = document.getElementById('vatChart');
        if (!canvas || typeof Chart === 'undefined') return;
        if(window.vatChartInstance) window.vatChartInstance.destroy();

        window.vatChartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{ data: @json($trendValues), borderColor: '#713FCE', backgroundColor: 'rgba(113, 63, 206, 0.1)', fill: true, tension: 0.1, pointRadius: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false }, ticks: { color: '#697c9a', maxTicksLimit: 8 } }, y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } } }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initVatChart);
    document.addEventListener('turbo:load', initVatChart);
</script>
@endpush

