@extends('manage.layouts.workspace')
@section('title', 'Reports')
@section('heading', 'Reports')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6" x-data="{ customRange: {{ $period === 'custom' ? 'true' : 'false' }} }">
    
    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        @php
            $filters = [
                'today' => 'Today',
                'week' => 'This week',
                'month' => 'This month',
                'all' => 'All time'
            ];
        @endphp

        @foreach($filters as $key => $label)
            <a href="{{ route('manage.workspace.cashbox', ['page' => 'reports', 'period' => $key]) }}" 
               class="px-5 py-2 rounded-full text-sm font-semibold flex items-center gap-2 transition
               {{ $period === $key ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                @if($period === $key) <i class="fa-solid fa-check"></i> @endif {{ $label }}
            </a>
        @endforeach

        <!-- Custom Range Toggle -->
        <button @click="customRange = !customRange" 
                class="px-5 py-2 rounded-full text-sm font-semibold flex items-center gap-2 transition
                {{ $period === 'custom' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
            <i class="fa-regular fa-calendar"></i> Custom range
        </button>
    </div>

    <!-- Custom Range Form (Hidden by default) -->
    <div x-show="customRange" x-cloak x-transition class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('manage.workspace.cashbox', ['page' => 'reports']) }}" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="period" value="custom">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Start Date</label>
                <input type="date" name="from" value="{{ $from }}" required class="border-gray-300 rounded-lg focus:ring-[#713FCE] focus:border-[#713FCE]">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">End Date</label>
                <input type="date" name="to" value="{{ $to }}" required class="border-gray-300 rounded-lg focus:ring-[#713FCE] focus:border-[#713FCE]">
            </div>
            <button type="submit" class="bg-[#713FCE] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#5a2ea6] transition">
                Apply Range
            </button>
        </form>
    </div>

    <!-- Download Buttons -->
    <div class="flex flex-wrap gap-3 mb-8">
        <button class="px-6 py-2.5 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition">
            <i class="fa-regular fa-file-pdf text-[#713FCE]"></i> Download PDF
        </button>
        <button class="px-6 py-2.5 rounded-xl border border-gray-400 text-gray-700 font-medium flex items-center gap-2 hover:bg-gray-50 transition">
            <i class="fa-regular fa-file-excel text-[#713FCE]"></i> Download Excel
        </button>
    </div>

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">CASH IN</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-arrow-down"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['cash_in'], 0) }}</p>
            <p class="text-xs text-gray-500">Cash received</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">CASH OUT</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-arrow-up"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['cash_out'], 0) }}</p>
            <p class="text-xs text-gray-500">Cash paid out</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">MPESA IN</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-mobile-screen"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['mpesa_in'], 0) }}</p>
            <p class="text-xs text-gray-500">Mobile money rec.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">MPESA OUT</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-file-export"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['mpesa_out'], 0) }}</p>
            <p class="text-xs text-gray-500">Mobile money paid out</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NET CASH</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['net_cash'], 0) }}</p>
            <p class="text-xs text-gray-500">Received less paid out</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NET MPESA</p>
                <div class="w-8 h-8 rounded-lg bg-[#f4effc] text-[#713FCE] flex items-center justify-center"><i class="fa-solid fa-mobile-screen"></i></div>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($reports['net_mpesa'], 0) }}</p>
            <p class="text-xs text-gray-500">Received less paid out</p>
        </div>
    </div>

    <!-- Money Flow -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Money flow</h3>
        <p class="text-sm text-gray-500 mb-8">Movement in this period • excludes opening balances</p>
        
        @php
            // Calculate the max value to make the progress bars relative
            $maxFlow = max($reports['cash_in'], $reports['cash_out'], $reports['mpesa_in'], $reports['mpesa_out'], 1);
        @endphp

        <div class="space-y-6">
            <div>
                <div class="flex justify-between text-sm font-medium text-gray-900 mb-2">
                    <span>Cash in</span>
                    <span>{{ number_format($reports['cash_in'], 0) }}</span>
                </div>
                <div class="w-full bg-[#f8f9fa] h-2 rounded-full overflow-hidden">
                    <div class="bg-[#713FCE] h-full rounded-full transition-all duration-500" style="width: {{ ($reports['cash_in'] / $maxFlow) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm font-medium text-gray-900 mb-2">
                    <span>Cash out</span>
                    <span>{{ number_format($reports['cash_out'], 0) }}</span>
                </div>
                <div class="w-full bg-[#f8f9fa] h-2 rounded-full overflow-hidden">
                    <div class="bg-[#713FCE] h-full rounded-full transition-all duration-500" style="width: {{ ($reports['cash_out'] / $maxFlow) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm font-medium text-gray-900 mb-2">
                    <span>Mpesa in</span>
                    <span>{{ number_format($reports['mpesa_in'], 0) }}</span>
                </div>
                <div class="w-full bg-[#f8f9fa] h-2 rounded-full overflow-hidden">
                    <div class="bg-[#713FCE] h-full rounded-full transition-all duration-500" style="width: {{ ($reports['mpesa_in'] / $maxFlow) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm font-medium text-gray-900 mb-2">
                    <span>Mpesa out</span>
                    <span>{{ number_format($reports['mpesa_out'], 0) }}</span>
                </div>
                <div class="w-full bg-[#f8f9fa] h-2 rounded-full overflow-hidden">
                    <div class="bg-[#713FCE] h-full rounded-full transition-all duration-500" style="width: {{ ($reports['mpesa_out'] / $maxFlow) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Income by category</h3>
            <p class="text-sm text-gray-500 mb-6">Where money came from</p>
            
            @if($reports['categories_in']->isEmpty())
                <p class="text-center text-gray-500 py-8">No income in this period.</p>
            @else
                <div class="space-y-4">
                    @foreach($reports['categories_in'] as $cat)
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-700">{{ $cat->name }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($cat->total, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Expenses by category</h3>
            <p class="text-sm text-gray-500 mb-6">Where money went</p>
            
            @if($reports['categories_out']->isEmpty())
                <p class="text-center text-gray-500 py-8">No expenses in this period.</p>
            @else
                <div class="space-y-4">
                    @foreach($reports['categories_out'] as $cat)
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-700">{{ $cat->name }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($cat->total, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection