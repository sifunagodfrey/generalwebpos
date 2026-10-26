@extends('manage.layouts.workspace')
@php
    $titles = [
        'quotations' => 'Quotations',
        'proforma' => 'Proforma Invoices',
        'invoices' => 'Invoices',
        'delivery' => 'Delivery Notes',
    ];
    $title = $titles[$page] ?? 'Documents';
@endphp
@section('title', $title)
@section('heading', $title)

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-6">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" placeholder="Search by {{ Str::singular(strtolower($title)) }} # or customer" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-lg">
        </div>
    </div>

    <div class="space-y-0">
        @forelse($records as $record)
            <div class="flex items-center justify-between py-4 border-b border-gray-200 last:border-0 cursor-pointer hover:bg-gray-50 transition px-2">
                <div class="flex items-center gap-4">
                    <div class="w-10 flex justify-center text-[#713FCE]">
                        @if($page === 'invoices') <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                        @elseif($page === 'quotations') <i class="fa-solid fa-file-lines text-2xl"></i>
                        @elseif($page === 'proforma') <i class="fa-solid fa-file-invoice text-2xl"></i>
                        @else <i class="fa-solid fa-truck-fast text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 capitalize">{{ Str::singular(str_replace('_', ' ', $record->type)) }} #{{ $record->id }}</p>
                        <p class="text-sm text-gray-500">{{ trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?: 'Walk-in customer' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($record->total, 0) }}</p>
                        <p class="text-sm text-green-600 capitalize">{{ $record->status === 'completed' ? 'Paid' : $record->status }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-900 text-lg"></i>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">No {{ strtolower($title) }} found.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection