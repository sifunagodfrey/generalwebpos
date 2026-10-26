@extends('manage.layouts.workspace')
@section('title', 'Overview')
@section('heading', 'Overview')
@section('subtitle', 'Every report, at a glance')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-900">Business performance</h2>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm font-medium text-gray-700 flex items-center gap-2 hover:bg-gray-50 transition">
                <i class="fa-regular fa-calendar text-[#713FCE]"></i> This month <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>
            <button onclick="window.location.reload()" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-xl transition">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>

    <!-- Banner -->
    <div class="p-4 rounded-xl mb-6 flex items-center gap-3 {{ $bannerType === 'success' ? 'bg-green-50 text-green-700' : ($bannerType === 'danger' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-700') }}">
        <i class="fa-solid {{ $bannerType === 'success' ? 'fa-arrow-trend-up' : ($bannerType === 'danger' ? 'fa-arrow-trend-down' : 'fa-arrow-right') }}"></i>
        <span class="font-medium">New activity • {{ $bannerMessage }}</span>
    </div>

    <!-- Download Buttons -->
    <div class="flex flex-wrap gap-3 mb-6">
        <button class="px-5 py-2 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition bg-white">
            <i class="fa-regular fa-file-pdf text-[#713FCE]"></i> Download PDF
        </button>
        <button class="px-5 py-2 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition bg-white">
            <i class="fa-regular fa-file-excel text-[#713FCE]"></i> Download Excel
        </button>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        <!-- Metric Cards -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sales</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-chart-simple"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalSales, 0) }}</p>
            <p class="text-xs text-gray-500">{{ $salesCount }} completed sales • <a href="{{ route('manage.workspace.reports', ['page' => 'sales']) }}" class="text-[#713FCE]">View sales</a></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue EX VAT</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-arrow-trend-up"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalSales - $totalVat, 0) }}</p>
            <p class="text-xs text-gray-500">Before costs • <a href="{{ route('manage.workspace.reports', ['page' => 'profit-loss']) }}" class="text-[#713FCE]">Profit & Loss</a></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">VAT</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalVat, 0) }}</p>
            <p class="text-xs text-gray-500">Tax on completed sales • <a href="{{ route('manage.workspace.reports', ['page' => 'vat']) }}" class="text-[#713FCE]">View VAT</a></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Products Sold</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-box"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $productsSold }}</p>
            <p class="text-xs text-gray-500">Distinct products • <a href="{{ route('manage.workspace.reports', ['page' => 'products']) }}" class="text-[#713FCE]">View products</a></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selling Staff</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-id-badge"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $staffWithSales }}</p>
            <p class="text-xs text-gray-500">Staff with completed sales • <a href="{{ route('manage.workspace.reports', ['page' => 'staff']) }}" class="text-[#713FCE]">View staff</a></p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Shifts Started</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-piggy-bank"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $shiftsStarted }}</p>
            <p class="text-xs text-gray-500">Opened in period • <a href="{{ route('manage.workspace.reports', ['page' => 'shifts']) }}" class="text-[#713FCE]">View shifts</a></p>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <!-- Sales Performance Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Sales performance</h3>
                    <p class="text-sm text-gray-500">Completed sales • This month</p>
                </div>
                <a href="{{ route('manage.workspace.reports', ['page' => 'sales']) }}" class="text-[#713FCE] font-medium hover:underline text-sm">Sales report</a>
            </div>
            <div class="flex gap-6 mb-6">
                <div><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-900">{{ number_format($totalSales, 0) }}</p></div>
                <div><p class="text-xs text-gray-500 uppercase">Daily Average</p><p class="text-2xl font-bold text-gray-900">{{ number_format($totalSales / max(now()->daysInMonth, 1), 0) }}</p></div>
                <div><p class="text-xs text-gray-500 uppercase">Best Day</p><p class="text-2xl font-bold text-gray-900">{{ number_format(max($trendValues), 0) }}</p></div>
            </div>
            <div class="h-64"><canvas id="overviewSalesChart"></canvas></div>
        </div>

        <!-- Payment Mix Donut -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Payment mix</h3>
            <p class="text-sm text-gray-500 mb-6">Recorded payments against sales completed in this period</p>
            <div class="relative w-48 h-48 mx-auto mb-6">
                <canvas id="overviewPaymentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-xs text-gray-500 uppercase">Collected</span>
                    <span class="text-xl font-bold text-gray-900">{{ number_format($totalSales, 0) }}</span>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($paymentMix as $pm)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $loop->first ? '#713FCE' : ($loop->iteration == 2 ? '#b388ff' : '#10b981') }}"></span>
                            <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $pm->method) }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ number_format($pm->total, 0) }} • {{ number_format(($pm->total / max($totalSales, 1)) * 100, 0) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Sellers -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Top sellers</h3>
                <p class="text-sm text-gray-500">Top 5 products or variants by revenue</p>
            </div>
            <a href="{{ route('manage.workspace.reports', ['page' => 'products']) }}" class="text-[#713FCE] font-medium hover:underline text-sm">Product report</a>
        </div>
        <div class="space-y-4">
            @foreach($topSellers as $index => $seller)
                <div class="flex justify-between items-center p-2 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $seller->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($seller->units_sold) }} units sold</p>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">{{ number_format($seller->revenue, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initOverviewCharts() {
        // Sales Chart
        const ctxSales = document.getElementById('overviewSalesChart');
        if (ctxSales && typeof Chart !== 'undefined') {
            if(window.ovSalesChart) window.ovSalesChart.destroy();
            window.ovSalesChart = new Chart(ctxSales, {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [{
                        data: @json($trendValues),
                        borderColor: '#713FCE',
                        backgroundColor: 'rgba(113, 63, 206, 0.1)',
                        fill: true,
                        tension: 0.1,
                        pointRadius: 0,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#697c9a', maxTicksLimit: 8 } },
                        y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } }
                    }
                }
            });
        }

        // Payment Chart
        const ctxPay = document.getElementById('overviewPaymentChart');
        if (ctxPay && typeof Chart !== 'undefined') {
            if(window.ovPayChart) window.ovPayChart.destroy();
            window.ovPayChart = new Chart(ctxPay, {
                type: 'doughnut',
                data: {
                    labels: @json($paymentMix->pluck('method')),
                    datasets: [{
                        data: @json($paymentMix->pluck('total')),
                        backgroundColor: ['#713FCE', '#b388ff', '#10b981', '#f59e0b'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }
    }

    // Initialize on both standard load and Turbo navigation
    document.addEventListener('DOMContentLoaded', initOverviewCharts);
    document.addEventListener('turbo:load', initOverviewCharts);
</script>
@endpush