@extends('manage.layouts.workspace')
@section('title', 'Reports')
@section('heading', 'Reports')
@section('mobile_heading', 'Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
@endpush

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

    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Revenue by staff</h3>
        <p class="text-sm text-gray-500 mb-6">This month</p>
        
        <div class="flex gap-6 mb-6">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($topRevenue, 0) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Daily Average</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($topRevenue / max(now()->daysInMonth, 1), 0) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Best Day</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($topRevenue, 0) }}</p>
            </div>
        </div>

        <div class="h-64 w-full">
            <canvas id="revenueChart"></canvas>
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
                        <div class="w-8 h-8 rounded-full bg-[#f4effc] text-[#713FCE] flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $staff ? $staff->first_name . ' ' . $staff->last_name : 'Unknown Staff' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $entry->sales_count }} sale(s)
                            </p>
                        </div>
                    </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    function initStaffReportsChart() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas || typeof Chart === 'undefined') return;

        // Destroy existing instance if navigating back and forth
        if (window.staffReportsChart) window.staffReportsChart.destroy();

        const leaderboard = @json($leaderboard);

        // Format names and extract revenue
        const labels = leaderboard.map(entry => {
            const staff = entry.staff;
            return staff ? staff.first_name + ' ' + (staff.last_name ? staff.last_name.substring(0, 1) + '...' : '') : 'Unknown';
        });
        
        const data = leaderboard.map(entry => parseFloat(entry.revenue));

        window.staffReportsChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: data,
                    backgroundColor: '#713FCE',
                    borderColor: '#713FCE',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    showLine: false // This creates the scatter effect shown in your screenshot
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#697c9a',
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f4effb' },
                        ticks: { color: '#697c9a' }
                    }
                }
            }
        });
    }

    // Initialize on both standard load and Turbo navigation
    document.addEventListener('DOMContentLoaded', initStaffReportsChart);
    document.addEventListener('turbo:load', initStaffReportsChart);
</script>
@endpush