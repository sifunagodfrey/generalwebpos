@extends('manage.layouts.workspace')
@section('title', 'Shift History')
@section('heading', 'Shift History')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    <!-- Search & Filter -->
    <div class="mb-6">
        <form method="GET" action="{{ route('manage.workspace.cashbox', ['page' => 'history']) }}" class="relative mb-4">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search staff or shift number" class="w-full pl-11 pr-4 py-3 rounded-none border-0 border-b border-gray-300 bg-transparent focus:ring-0 focus:border-[#713FCE] outline-none transition text-lg">
        </form>
        
        <div class="flex flex-wrap items-center gap-2 md:justify-end">
            <a href="{{ route('manage.workspace.cashbox', ['page' => 'history']) }}" class="px-5 py-2 rounded-full text-sm font-semibold {{ !request('status') ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }} flex items-center gap-2">
                @if(!request('status')) <i class="fa-solid fa-check"></i> @endif All shifts
            </a>
            <a href="{{ route('manage.workspace.cashbox', ['page' => 'history', 'status' => 'open']) }}" class="px-5 py-2 rounded-full text-sm font-semibold {{ request('status') === 'open' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Open</a>
            <a href="{{ route('manage.workspace.cashbox', ['page' => 'history', 'status' => 'closed']) }}" class="px-5 py-2 rounded-full text-sm font-semibold {{ request('status') === 'closed' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Closed</a>
            <button class="px-5 py-2 rounded-full text-sm font-semibold bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                <i class="fa-regular fa-calendar text-[#713FCE]"></i> Date range
            </button>
        </div>
    </div>

    <!-- List -->
    <div class="space-y-3">
        @forelse($shifts as $shift)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 flex items-center justify-center {{ $shift->is_closed ? 'text-gray-400' : 'text-green-500' }}">
                        <i class="fa-solid {{ $shift->is_closed ? 'fa-lock' : 'fa-lock-open' }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">Shift #{{ $shift->id }} • {{ $shift->first_name }} {{ $shift->last_name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $shift->is_closed ? 'Closed' : 'Open' }} • Started {{ \Carbon\Carbon::parse($shift->opened_at)->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Ended: {{ $shift->closed_at ? \Carbon\Carbon::parse($shift->closed_at)->format('d/m/Y H:i') : 'Still open' }}
                        </p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
                No shift history found.
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">{{ $shifts->links() }}</div>
</div>
@endsection