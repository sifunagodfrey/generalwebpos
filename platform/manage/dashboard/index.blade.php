<!-- platform/manage/dashboard/index.blade.php -->
@extends('manage.layouts.workspace')
@section('title', 'Dashboard')
@section('subtitle', 'Today at a glance')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swi-wrap swi-report-wrap">
    
    {{-- 1. Top Metrics --}}
    <div class="swi-report-metrics">
        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>TODAY'S SALES</span>
                <div class="swi-rm-icon purple"><i class="fa-solid fa-cash-register"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($todaySales) }}</div>
            <div class="swi-rm-label">
                @if($salesChange > 0)
                    <span style="color: #10b981;"><i class="fa-solid fa-arrow-up"></i> {{ number_format($salesChange, 0) }}%</span>
                @elseif($salesChange < 0)
                    <span style="color: #ef4444;"><i class="fa-solid fa-arrow-down"></i> {{ number_format(abs($salesChange), 0) }}%</span>
                @else
                    <span style="color: #697c9a;">No change</span>
                @endif
                vs yesterday
            </div>
        </div>

        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>TRANSACTIONS</span>
                <div class="swi-rm-icon purple"><i class="fa-solid fa-receipt"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($todayTx) }}</div>
            <div class="swi-rm-label">
                @if($txChange > 0)
                    <span style="color: #10b981;"><i class="fa-solid fa-arrow-up"></i> {{ number_format($txChange, 0) }}%</span>
                @elseif($txChange < 0)
                    <span style="color: #ef4444;"><i class="fa-solid fa-arrow-down"></i> {{ number_format(abs($txChange), 0) }}%</span>
                @else
                    <span style="color: #697c9a;">No change</span>
                @endif
                vs yesterday
            </div>
        </div>

        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>LOW STOCK</span>
                <div class="swi-rm-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($lowStockCount) }}</div>
            <div class="swi-rm-label">{{ $lowStockCount > 0 ? 'Items need attention' : 'All good' }}</div>
        </div>

        <div class="swi-rm-card">
            <div class="swi-rm-top">
                <span>CUSTOMERS OWING</span>
                <div class="swi-rm-icon green"><i class="fa-solid fa-wallet"></i></div>
            </div>
            <div class="swi-rm-value">{{ number_format($owingCount) }}</div>
            <div class="swi-rm-label">Outstanding {{ number_format($owingTotal) }}</div>
        </div>
    </div>

    {{-- 2. Charts Grid --}}
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

    {{-- 3. Lists --}}
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

    <div class="swi-report-card">
        <div class="swi-rc-header" style="margin-bottom: 16px;">
            <h3>Needs attention</h3>
        </div>
        <div class="swi-dash-list">
            @forelse($needsAttention as $customer)
                <div class="swi-dash-row">
                    <div class="swi-attention-icon"><i class="fa-regular fa-user"></i></div>
                    <div class="swi-dash-info">
                        <strong>{{ trim($customer->first_name . ' ' . $customer->last_name) }}</strong>
                    </div>
                    <div class="swi-dash-val" style="color: #ef4444;">owes {{ number_format($customer->credit_balance) }}</div>
                </div>
            @empty
                <div class="swi-empty" style="padding: 20px 0; text-align: left;">No outstanding balances.</div>
            @endforelse
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initDashboardCharts() {
        const trendCanvas = document.getElementById('salesTrendChart');
        const mixCanvas = document.getElementById('paymentMixChart');
        if (!trendCanvas || !mixCanvas || typeof Chart === 'undefined') return;

        if (window.dashTrend) window.dashTrend.destroy();
        if (window.dashMix) window.dashMix.destroy();

        // Sales Trend Chart
        window.dashTrend = new Chart(trendCanvas.getContext('2d'), {
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
                    tension: 0.1
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
        window.dashMix = new Chart(mixCanvas.getContext('2d'), {
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

    initDashboardCharts();
    document.addEventListener('turbo:load', initDashboardCharts);
</script>
@endsection