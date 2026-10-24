@extends('manage.layouts.workspace')
@section('title', 'Reports')
@section('heading', 'Reports')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swi-wrap swi-report-wrap">
    
    {{-- Top Controls --}}
    <div class="swi-report-controls">
        <div class="swi-report-date">
            <i class="fa-regular fa-calendar"></i>
            <span>This month</span>
            <i class="fa-solid fa-chevron-down" style="font-size:10px; margin-left:8px;"></i>
        </div>
        <button class="swi-report-refresh" title="Refresh" onclick="window.location.reload()">
            <i class="fa-solid fa-rotate-right"></i>
        </button>
    </div>

    <div class="swi-report-actions">
        <button class="swi-report-export">
            <i class="fa-regular fa-file-pdf"></i> Download PDF
        </button>
        <button class="swi-report-export">
            <i class="fa-regular fa-file-excel"></i> Download Excel
        </button>
    </div>

    {{-- Metric Cards --}}
    <div class="swi-report-metrics">
        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>PRODUCTS</span>
                <div class="swi-rm-icon purple"><i class="fa-solid fa-box"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($productsCount) }}</div>
            <div class="swi-rm-label">Active catalog</div>
        </div>
        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>STOCK VALUE</span>
                <div class="swi-rm-icon purple"><i class="fa-solid fa-money-bill"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($stockValue) }}</div>
            <div class="swi-rm-label">Products and variants</div>
        </div>
        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>LOW STOCK</span>
                <div class="swi-rm-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($lowStockCount) }}</div>
            <div class="swi-rm-label">At configured thresholds</div>
        </div>
        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>OUT OF STOCK</span>
                <div class="swi-rm-icon red"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($outOfStockCount) }}</div>
            <div class="swi-rm-label">Items needing replenishment</div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="swi-report-card">
        <div class="swi-rc-header">
            <h3>Revenue trend</h3>
            <p>This month &bull; completed sales</p>
        </div>
        <div class="swi-rc-stats">
            <div><span>TOTAL</span><strong>{{ number_format($totalRevenue) }}</strong></div>
            <div><span>DAILY AVERAGE</span><strong>{{ number_format($dailyAverage) }}</strong></div>
            <div><span>BEST DAY</span><strong>{{ number_format($bestDay) }}</strong></div>
        </div>
        <div class="swi-chart-container" style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Top Sellers --}}
    <div class="swi-report-card">
        <div class="swi-rc-header">
            <h3>Top sellers</h3>
            <p>This month &bull; ranked by revenue</p>
        </div>
        <div class="swi-top-sellers">
            @foreach($topSellers as $index => $seller)
                <div class="swi-ts-row">
                    <div class="swi-ts-rank">{{ $index + 1 }}</div>
                    <div class="swi-ts-body">
                        <div class="swi-ts-info">
                            <div>
                                <strong>{{ $seller->name }}</strong>
                                <small>{{ number_format($seller->units_sold) }} units sold</small>
                            </div>
                            <span>{{ number_format($seller->revenue) }}</span>
                        </div>
                        <div class="swi-ts-progress-bg">
                            <div class="swi-ts-progress-fill" style="width: {{ ($seller->revenue / $maxSellerRevenue) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Stock Value by Category --}}
    <div class="swi-report-card">
        <div class="swi-rc-header">
            <h3>Stock value by category</h3>
            <p>Top categories and others &bull; includes variant stock at cost</p>
        </div>
        <div class="swi-donut-layout">
            <div class="swi-donut-wrapper">
                <canvas id="categoryChart" width="160" height="160"></canvas>
                <div class="swi-donut-center">
                    <span>STOCK VALUE</span>
                    <strong>{{ number_format($stockValue) }}</strong>
                </div>
            </div>
            <div class="swi-donut-legend">
                @foreach($categoryValues as $cat)
                    <div class="swi-legend-row">
                        <div class="swi-legend-name">
                            <span class="swi-dot" style="background: {{ $loop->first ? '#713fce' : '#d1c4e9' }}"></span>
                            {{ $cat->category_name }}
                        </div>
                        <span class="swi-legend-value">{{ number_format($cat->value) }} &bull; {{ number_format(($cat->value / $totalCategoryValue) * 100, 0) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stock Health Action --}}
    <div class="swi-report-card" style="padding-bottom: 32px;">
        <div class="swi-rc-header" style="margin-bottom: 16px;">
            <h3>Stock health</h3>
            <p>Product and variant thresholds</p>
        </div>
        <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts']) }}" class="swi-stock-health-btn">
            <i class="fa-regular fa-bell"></i> View and filter stock alerts
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initInventoryCharts() {
        const revCanvas = document.getElementById('revenueChart');
        const catCanvas = document.getElementById('categoryChart');

        // Wait until both canvases exist in the DOM and Chart.js is loaded
        if (!revCanvas || !catCanvas || typeof Chart === 'undefined') return;

        // Destroy existing instances (crucial for Turbo/Livewire page transitions)
        if (window.invRevChart) window.invRevChart.destroy();
        if (window.invCatChart) window.invCatChart.destroy();

        // 1. Revenue Line Chart
        const ctxRevenue = revCanvas.getContext('2d');
        window.invRevChart = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    data: @json($trendValues),
                    borderColor: '#713fce',
                    backgroundColor: 'rgba(113, 63, 206, 0.1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#697c9a', maxTicksLimit: 8 } },
                    y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } }
                }
            }
        });

        // 2. Category Donut Chart
        const ctxCategory = catCanvas.getContext('2d');
        window.invCatChart = new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: @json($categoryValues->pluck('category_name')),
                datasets: [{
                    data: @json($categoryValues->pluck('value')),
                    backgroundColor: ['#713fce', '#d1c4e9', '#eaddfd', '#f4effc'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    // Attach to both standard load and Turbo transitions
    document.addEventListener('DOMContentLoaded', initInventoryCharts);
    document.addEventListener('turbo:load', initInventoryCharts);
</script>
@endpush