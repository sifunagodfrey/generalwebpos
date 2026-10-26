@extends('manage.layouts.workspace')
@section('title', 'Sales')
@section('heading', 'Sales')
@section('subtitle', '')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <!-- Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sales</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-receipt"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($count, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($subtotal, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">VAT Collected</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($vat, 0) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-cash-register"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($total, 0) }}</p>
        </div>
    </div>

    <!-- By Payment Method -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">By payment method</h3>
        <div class="flex flex-col md:flex-row items-center gap-y-12 md:gap-x-12">
            <div class="relative w-40 h-40">
                <canvas id="salesPaymentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-xs text-gray-500 uppercase">Collected</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($total, 0) }}</span>
                </div>
            </div>
            <div class="flex-1 w-full space-y-3">
                @foreach($paymentMix as $pm)
                    <div class="flex justify-between items-center text-sm border-b border-gray-100 pb-2 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $loop->first ? '#713FCE' : ($loop->iteration == 2 ? '#b388ff' : '#10b981') }}"></span>
                            <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $pm->method) }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ number_format($pm->total, 0) }} • {{ number_format(($pm->total / max($total, 1)) * 100, 0) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Revenue Trend -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Revenue</h3>
                <p class="text-sm text-gray-500">This month</p>
            </div>
        </div>
        <div class="flex gap-6 mb-6">
            <div><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-900">{{ number_format($total, 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Daily Average</p><p class="text-2xl font-bold text-gray-900">{{ number_format($total / max(now()->daysInMonth, 1), 0) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Best Day</p><p class="text-2xl font-bold text-gray-900">{{ number_format(max($trendValues), 0) }}</p></div>
        </div>
        <div class="h-64"><canvas id="salesRevenueChart"></canvas></div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Top products</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        <div class="space-y-4">
            @foreach($topProducts as $product)
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 last:border-0">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->units_sold }} sold</p>
                    </div>
                    <p class="font-semibold text-gray-900">{{ number_format($product->revenue, 0) }}</p>
                </div>
            @endforeach
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initSalesCharts() {
        const ctxPay = document.getElementById('salesPaymentChart');
        if (ctxPay && typeof Chart !== 'undefined') {
            if(window.salesPayChart) window.salesPayChart.destroy();
            window.salesPayChart = new Chart(ctxPay, {
                type: 'doughnut',
                data: {
                    labels: @json($paymentMix->pluck('method')),
                    datasets: [{ data: @json($paymentMix->pluck('total')), backgroundColor: ['#713FCE', '#b388ff', '#10b981', '#f59e0b'], borderWidth: 0, cutout: '75%' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        const ctxRev = document.getElementById('salesRevenueChart');
        if (ctxRev && typeof Chart !== 'undefined') {
            if(window.salesRevChart) window.salesRevChart.destroy();
            window.salesRevChart = new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [{ data: @json($trendValues), borderColor: '#713FCE', backgroundColor: 'rgba(113, 63, 206, 0.1)', fill: true, tension: 0.1, pointRadius: 0 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { color: '#697c9a', maxTicksLimit: 8 } }, y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } } } }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initSalesCharts);
    document.addEventListener('turbo:load', initSalesCharts);
</script>
@endpush