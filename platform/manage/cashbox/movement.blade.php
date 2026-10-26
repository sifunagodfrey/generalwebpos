@extends('manage.layouts.workspace')
@section('title', 'Cash Movement')
@section('heading', 'Cash Movement')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6" x-data="{ 
    detailModal: false, 
    selected: {}, 
    openDetail(item) { this.selected = item; this.detailModal = true; } 
}">
    
    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-4">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" placeholder="Search cash movements" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-lg">
        </div>
    </div>

    <!-- List -->
    <div class="space-y-3">
        @forelse($movements as $item)
            <div @click="openDetail(@js($item))" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 flex justify-center {{ $item->is_in ? 'text-green-600' : 'text-red-500' }}">
                        <i class="fa-solid {{ $item->is_in ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xl font-bold"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500">{{ $item->subtitle }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-lg font-bold {{ $item->is_in ? 'text-green-600' : 'text-red-500' }}">
                        {{ $item->is_in ? '+' : '-' }}{{ number_format($item->amount, 0) }}
                    </span>
                    <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">
                No cash movements found.
            </div>
        @endforelse
    </div>

    <!-- Detail Modal -->
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
                <a :href="selected.sale_id ? '/workspace/sale/history/' + selected.sale_id : '#'" x-show="selected.sale_id" class="text-[#713FCE] font-semibold hover:bg-black/5 px-4 py-2 rounded-full transition">View sale</a>
                <button type="button" @click="detailModal = false" class="bg-[#713FCE] hover:bg-[#5a2ea6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow-sm">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection