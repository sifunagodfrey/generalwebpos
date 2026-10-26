@extends('manage.layouts.workspace')
@section('title', $item[1] ?? 'Cash Box')
@section('heading', $item[1] ?? 'Cash Box')
@section('subtitle', "This device's current shift")

@section('content')
<div class="max-w-7xl mx-auto space-y-4 pb-20 md:pb-6">

    @if($shift)
        {{-- Shift Status Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Shift open</h2>
                    <p class="text-sm text-gray-500 mt-1">Shift #{{ $shift->id }} • Opened {{ \Carbon\Carbon::parse($shift->opened_at)->format('d/m/Y H:i') }}</p>
                </div>
                
                {{-- Mobile End Shift (full width) --}}
                <div class="md:hidden">
                    <a href="{{ route('manage.workspace.cashbox', ['page' => 'index']) }}" class="block w-full text-center bg-[#f4effc] text-[#713FCE] font-semibold py-3 rounded-xl hover:bg-[#eaddfd] transition">
                        End shift
                    </a>
                </div>
                
                {{-- Desktop End Shift (top right) --}}
                <div class="hidden md:block">
                    <a href="{{ route('manage.workspace.cashbox', ['page' => 'index']) }}" class="bg-[#f4effc] text-[#713FCE] font-semibold px-6 py-2 rounded-xl hover:bg-[#eaddfd] transition">
                        End shift
                    </a>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total on hand</p>
                <p class="text-4xl font-bold text-[#713FCE]">{{ number_format($totalOnHand, 0) }}</p>
            </div>

            <p class="text-sm text-gray-500 mb-6">Expected balance = opening float + money received – money paid out</p>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 transition shadow-sm">
                    <i class="fa-solid fa-plus"></i> Money in
                </button>
                <button type="button" class="border border-[#713FCE] text-[#713FCE] hover:bg-[#f4effc] px-6 py-2.5 rounded-xl font-medium flex items-center gap-2 transition">
                    <i class="fa-solid fa-minus"></i> Money out
                </button>
                <a href="#" class="text-[#713FCE] font-medium flex items-center gap-2 px-4 py-2.5 hover:bg-gray-50 rounded-xl transition">
                    <i class="fa-solid fa-chart-simple"></i> View reports
                </a>
            </div>
        </div>

        {{-- Summary Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            {{-- Cash on hand --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Cash on<br>Hand</p>
                    <div class="w-9 h-9 rounded-xl bg-[#f4effc] text-[#713FCE] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($cashOnHand, 0) }}</p>
                    <p class="text-xs text-gray-500">Opening {{ number_format($shift->opening_cash, 0) }}</p>
                </div>
            </div>

            {{-- Mpesa on hand --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Mpesa on<br>Hand</p>
                    <div class="w-9 h-9 rounded-xl bg-[#f4effc] text-[#713FCE] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($mpesaOnHand, 0) }}</p>
                    <p class="text-xs text-gray-500">Opening {{ number_format($shift->opening_mobile_money, 0) }}</p>
                </div>
            </div>

            {{-- Money In --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Money In</p>
                    <div class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-arrow-down-left"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($moneyIn, 0) }}</p>
                    <p class="text-xs text-gray-500">Cash + Mpesa<br>received</p>
                </div>
            </div>

            {{-- Money Out --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Money Out</p>
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-arrow-up-right"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($moneyOut, 0) }}</p>
                    <p class="text-xs text-gray-500">Cash + Mpesa paid<br>out</p>
                </div>
            </div>

            {{-- Opening Float --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Opening<br>Float</p>
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($openingFloat, 0) }}</p>
                    <p class="text-xs text-gray-500">Cash + Mpesa at start</p>
                </div>
            </div>

            {{-- Net Movement --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider leading-tight">Net<br>Movement</p>
                    <div class="w-9 h-9 rounded-xl bg-[#f4effc] text-[#713FCE] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($netMovement, 0) }}</p>
                    <p class="text-xs text-gray-500">Received less paid out</p>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-2">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Recent activity</h3>
                    <p class="text-sm text-gray-500">Latest 20 receipts and manual movements in this shift</p>
                </div>
                <a href="#" class="text-[#713FCE] font-medium text-sm hover:underline">View all</a>
            </div>

            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition cursor-pointer">
                        <div class="flex items-center gap-4">
                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $activity->is_in ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500' }}">
                                <i class="fa-solid text-lg {{ $activity->is_in ? 'fa-arrow-down-left' : 'fa-arrow-up-right' }}"></i>
                            </div>
                            
                            {{-- Text --}}
                            <div>
                                <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->subtitle }}</p>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="font-bold text-lg {{ $activity->is_in ? 'text-green-600' : 'text-red-500' }}">
                            {{ $activity->is_in ? '+' : '-' }}{{ number_format($activity->amount, 0) }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        No recent activity.
                    </div>
                @endforelse
            </div>
        </div>
    @else
        {{-- No Shift Fallback --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-[#f4effc] text-[#713FCE] flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">No open shift</h2>
            <p class="text-sm text-gray-500 mb-6">Open a shift to begin recording cash activity</p>
            <button class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-8 py-3 rounded-xl font-semibold transition shadow-sm" @click="$dispatch('sw:open-shift')">
                Open Shift
            </button>
        </div>
    @endif

</div>
@endsection