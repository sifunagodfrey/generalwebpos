@extends('manage.layouts.workspace')
@section('title', 'Income')
@section('heading', 'Income')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6" x-data="{ 
    recordModal: false, 
    detailModal: false, 
    method: 'cash',
    selected: {},
    openDetail(item) { this.selected = item; this.detailModal = true; } 
}">
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded-xl mb-4">{{ $errors->first() }}</div>
    @endif

    <!-- Search & Record Button -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 mb-4">
        <div class="relative mb-4">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" placeholder="Search cash movements" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-lg">
        </div>
        <button @click="recordModal = true" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Record income
        </button>
    </div>

    <!-- List -->
    <div class="space-y-3">
        @forelse($movements as $item)
            <div @click="openDetail(@js($item))" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 flex justify-center text-green-600">
                        <i class="fa-solid fa-arrow-down text-xl font-bold"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500">{{ $item->subtitle }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-lg font-bold text-green-600">+{{ number_format($item->amount, 0) }}</span>
                    <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
                No income records found.
            </div>
        @endforelse
    </div>

    <!-- Record Modal -->
    <div x-cloak x-show="recordModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50" @click.self="recordModal = false" x-transition.opacity>
        <div class="bg-[#f4effb] rounded-3xl w-full max-w-md p-8 relative shadow-xl" @click.stop>
            <form method="POST" action="{{ route('manage.cashbox.income.store') }}">
                @csrf
                <h3 class="text-2xl font-normal text-gray-900 mb-6">Cash in</h3>
                
                <div class="space-y-6">
                    <div class="relative">
                        <label class="text-xs text-[#713FCE] font-medium absolute -top-2 left-0">Amount</label>
                        <input type="number" name="amount" step="0.01" required autofocus class="w-full border-0 border-b-2 border-[#713FCE] bg-transparent focus:ring-0 px-0 py-2 text-xl text-gray-900 outline-none">
                    </div>

                    <!-- Custom Cash/Mpesa Toggle -->
                    <div class="flex p-1 bg-white border border-gray-300 rounded-full">
                        <label class="flex-1 text-center py-2 rounded-full cursor-pointer font-medium flex items-center justify-center gap-2 transition"
                               :class="method === 'cash' ? 'bg-[#f4effc] text-[#713FCE]' : 'text-gray-600 hover:bg-gray-50'">
                            <input type="radio" name="method" value="cash" x-model="method" class="hidden"> 
                            <i class="fa-solid" :class="method === 'cash' ? 'fa-check' : 'fa-money-bill'"></i> Cash
                        </label>
                        <label class="flex-1 text-center py-2 rounded-full cursor-pointer font-medium flex items-center justify-center gap-2 transition"
                               :class="method === 'mpesa' ? 'bg-[#f4effc] text-[#713FCE]' : 'text-gray-600 hover:bg-gray-50'">
                            <input type="radio" name="method" value="mpesa" x-model="method" class="hidden"> 
                            <i class="fa-solid fa-mobile-screen"></i> Mpesa
                        </label>
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-300 pb-2">
                        <select name="category_id" class="w-full border-0 bg-transparent focus:ring-0 px-0 py-1 text-gray-600 outline-none">
                            <option value="">Category (optional)</option>
                            {{-- Populate this from your DB if needed --}}
                            <option value="1">Sales</option>
                            <option value="2">Deposit</option>
                        </select>
                        <button type="button" class="text-[#713FCE] text-xl"><i class="fa-solid fa-circle-plus"></i></button>
                    </div>

                    <div class="relative">
                        <input type="text" name="reason" class="w-full border-0 border-b border-gray-300 bg-transparent focus:border-[#713FCE] focus:ring-0 px-0 py-2 text-gray-900 outline-none" placeholder="Reason (optional)">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-4 mt-8">
                    <button type="button" @click="recordModal = false" class="text-[#713FCE] font-semibold hover:bg-black/5 px-4 py-2 rounded-full transition">Cancel</button>
                    <button type="submit" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow-sm">Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal (Shared) -->
    <div x-cloak x-show="detailModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50" @click.self="detailModal = false" x-transition.opacity>
        <div class="bg-[#f4effb] rounded-3xl w-full max-w-md p-8 relative shadow-xl" @click.stop>
            <h3 class="text-2xl font-normal text-gray-900 mb-6" x-text="selected.title"></h3>
            <div class="space-y-4 mb-8">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-gray-500">Direction</span>
                    <span class="font-medium text-gray-900" x-text="selected.direction"></span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-gray-500">Type</span>
                    <span class="font-medium text-gray-900" x-text="selected.type"></span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-gray-500">Amount</span>
                    <span class="font-medium text-gray-900" x-text="selected.amount"></span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-gray-500">Date</span>
                    <span class="font-medium text-gray-900" x-text="selected.date_full"></span>
                </div>
            </div>
            <div class="flex justify-end items-center gap-4">
                <button type="button" @click="detailModal = false" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow-sm">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection