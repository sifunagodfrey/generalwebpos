@extends('manage.layouts.workspace')
@section('title', 'Sales Trends')
@section('subtitle', 'Last 7 days • completed sales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
    
    {{-- Added Mobile Overrides --}}
    <style>
        @media (max-width: 850px) {
            /* 1. Stack the main grid into a single column */
            .swi-dash-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }

            /* 2. Reduce card padding so it doesn't feel cramped */
            .swi-report-card {
                padding: 16px !important;
            }

            /* 3. Fix the top stats (Total, Avg, Best Day) so they fit the screen */
            .swi-rc-stats {
                gap: 16px !important;
                flex-wrap: wrap;
            }
            .swi-rc-stats strong {
                font-size: 20px !important;
            }
            .swi-rc-stats span {
                font-size: 10px !important;
            }

            /* 4. Adjust the chart height for mobile */
            .swi-chart-container {
                height: 240px !important;
                width: 100%;
            }
            #salesTrendChart {
                max-width: 100% !important;
            }

            /* 5. Ensure the donut chart and legend fit */
            .swi-donut-wrapper {
                margin-bottom: 24px;
            }
            .swi-legend-row {
                flex-direction: row;
                align-items: flex-start;
                gap: 8px;
            }
            .swi-legend-name {
                flex: 1;
            }
            .swi-legend-value {
                text-align: right;
                white-space: nowrap;
            }

            /* 6. Fix List Rows (Popular products & By day) */
            .swi-dash-row {
                padding: 12px 0 !important;
            }
            .swi-dash-info strong {
                font-size: 14px;
                word-break: break-word;
            }
            .swi-dash-val {
                white-space: nowrap;
                font-size: 14px;
            }
            .swi-ts-rank {
                width: 28px !important;
                height: 28px !important;
                font-size: 12px !important;
                margin-right: 12px;
            }
        }
    </style>
@endpush

@section('content')
<div class="swi-wrap swi-report-wrap">
    
    {{-- 1. Charts Grid --}}
    <div class="swi-dash-grid">
        
        {{-- Sales Trend --}}
        <div class="swi-report-card">
            <div class="swi-rc-header">
                <h3>Sales trend</h3>
                <p>Last 7 days &bull; completed sales</p>
            </div>
            <div class="swi-rc-stats">
                <div><span>TOTAL</span><strong>{{ number_format($trendTotal) }}</strong></div>
                <div><span>DAILY AVERAGE</span><strong>{{ number_format($trendAvg) }}</strong></div>
                <div><span>BEST DAY</span><strong>{{ number_format($trendBest) }}</strong></div>
            </div>
            <div class="swi-chart-container" style="height: 280px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        {{-- Payment Mix --}}
        <div class="swi-report-card">
            <div class="swi-rc-header">
                <h3>Payment mix</h3>
                <p>Last 7 days &bull; actual collections</p>
            </div>
            <div class="swi-donut-layout" style="flex-direction: column; align-items: stretch; margin-top: 10px;">
                <div class="swi-donut-wrapper" style="margin: 0 auto 20px;">
                    <canvas id="paymentMixChart" width="160" height="160"></canvas>
                    <div class="swi-donut-center">
                        <span>COLLECTED</span>
                        <strong>{{ number_format($paymentTotal) }}</strong>
                    </div>
                </div>
                <div class="swi-donut-legend">
                    @foreach($paymentMix as $pm)
                        <div class="swi-legend-row">
                            <div class="swi-legend-name">
                                <span class="swi-dot" style="background: {{ $loop->first ? '#713fce' : ($loop->iteration == 2 ? '#b388ff' : '#10b981') }}"></span>
                                {{ ucfirst(str_replace('_', ' ', $pm->method)) }}
                            </div>
                            <span class="swi-legend-value">{{ number_format($pm->total) }} &bull; {{ number_format(($pm->total / max($paymentTotal, 1)) * 100, 0) }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- 2. Popular Products List --}}
    <div class="swi-report-card">
        <div class="swi-rc-header">
            <h3>Popular products</h3>
            <p>Last 7 days &bull; ranked by revenue</p>
        </div>
        <div class="swi-dash-list">
            @forelse($popularProducts as $index => $product)
                <div class="swi-dash-row">
                    <div class="swi-ts-rank">{{ $index + 1 }}</div>
                    <div class="swi-dash-info">
                        <strong>{{ $product->name }}</strong>
                        <small>{{ number_format($product->units_sold) }} units sold</small>
                    </div>
                    <div class="swi-dash-val">{{ number_format($product->revenue) }}</div>
                </div>
            @empty
                <div class="swi-empty" style="padding: 20px 0; text-align: left;">No products sold in the last 7 days.</div>
            @endforelse
        </div>
    </div>

    {{-- 3. By Day List --}}
    <div class="swi-report-card">
        <div class="swi-rc-header" style="margin-bottom: 16px;">
            <h3>By day</h3>
        </div>
        <div class="swi-dash-list">
            @foreach($byDay as $day)
                <div class="swi-dash-row" style="border-bottom: 1px solid #f4effb;">
                    <div style="flex: 1; font-size: 15px; font-weight: 600; color: #2b3549;">
                        {{ $day['date'] }}
                    </div>
                    <div class="swi-dash-val">{{ number_format($day['total']) }}</div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initSalesTrendCharts() {
        const trendCanvas = document.getElementById('salesTrendChart');
        const mixCanvas = document.getElementById('paymentMixChart');
        if (!trendCanvas || !mixCanvas || typeof Chart === 'undefined') return;

        if (window.salesTrendChartObj) window.salesTrendChartObj.destroy();
        if (window.salesMixChartObj) window.salesMixChartObj.destroy();

        // Sales Trend Chart
        window.salesTrendChartObj = new Chart(trendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    data: @json($trendData),
                    borderColor: '#713fce',
                    backgroundColor: 'rgba(113, 63, 206, 0.05)',
                    borderWidth: 2,
                    pointBackgroundColor: '#713fce',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.1 // Keeps the smooth curve on desktop as requested
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#697c9a' } },
                    y: { beginAtZero: true, grid: { color: '#f4effb' }, ticks: { color: '#697c9a' } }
                }
            }
        });

        // Payment Mix Chart
        window.salesMixChartObj = new Chart(mixCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($paymentMix->pluck('method')),
                datasets: [{
                    data: @json($paymentMix->pluck('total')),
                    backgroundColor: ['#713fce', '#b388ff', '#10b981', '#f59e0b'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    initSalesTrendCharts();
    document.addEventListener('turbo:load', initSalesTrendCharts);
</script>
@endsection