@extends('manage.layouts.workspace')
@section('title', 'All Documents')
@section('heading', 'All Documents')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6" x-data="{ filter: 'all' }">
    
    <!-- Search Bar -->
    <form method="GET" action="{{ route('manage.workspace.documents', ['page' => 'index']) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-4">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by document # or customer" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-lg">
        </div>
    </form>

    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <button @click="filter = 'all'" class="px-5 py-2 rounded-full text-sm font-semibold flex items-center gap-2 transition" :class="filter === 'all' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'">
            <i class="fa-solid fa-check" x-show="filter === 'all'"></i> All types
        </button>
        <button @click="filter = 'quotation'" class="px-5 py-2 rounded-full text-sm font-semibold transition" :class="filter === 'quotation' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'">Quotations</button>
        <button @click="filter = 'proforma'" class="px-5 py-2 rounded-full text-sm font-semibold transition" :class="filter === 'proforma' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'">Proforma Invoices</button>
        <button @click="filter = 'invoice'" class="px-5 py-2 rounded-full text-sm font-semibold transition" :class="filter === 'invoice' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'">Invoices</button>
        <button @click="filter = 'delivery_note'" class="px-5 py-2 rounded-full text-sm font-semibold transition" :class="filter === 'delivery_note' ? 'bg-[#f4effc] text-[#713FCE]' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'">Delivery Notes</button>
    </div>

    <!-- List -->
    <div class="space-y-3">
        <!-- Documents -->
        @foreach($documents as $doc)
            <div x-show="filter === 'all' || filter === '{{ $doc->type }}'" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-[#713FCE] bg-[#f4effc]">
                        @if($doc->type === 'invoice') <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                        @elseif($doc->type === 'quotation') <i class="fa-solid fa-file-lines text-xl"></i>
                        @elseif($doc->type === 'proforma') <i class="fa-solid fa-file-invoice text-xl"></i>
                        @else <i class="fa-solid fa-truck-fast text-xl"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $doc->type) }} #{{ $doc->id }}</p>
                        <p class="text-sm text-gray-500">{{ trim(($doc->first_name ?? '') . ' ' . ($doc->last_name ?? '')) ?: 'Walk-in customer' }} - {{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($doc->total, 0) }}</p>
                        <p class="text-sm text-green-600">{{ $doc->status }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
                </div>
            </div>
        @endforeach

        <!-- Credit Notes -->
        @foreach($creditNotes as $cn)
            <div x-show="filter === 'all'" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-[#713FCE] bg-[#f4effc]">
                        <i class="fa-solid fa-file-shield text-xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">Credit Note #{{ str_pad($cn->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-500">{{ trim(($cn->first_name ?? '') . ' ' . ($cn->last_name ?? '')) ?: 'Walk-in customer' }} - {{ \Carbon\Carbon::parse($cn->returned_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($cn->amount_refunded, 0) }}</p>
                        <p class="text-sm text-green-600">issued</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection