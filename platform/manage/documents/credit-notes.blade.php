@extends('manage.layouts.workspace')
@section('title', 'Credit Notes')
@section('heading', 'Credit Notes')

@section('content')
<div class="max-w-7xl mx-auto pb-20 md:pb-6">
    
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Credit notes</h2>
        <p class="text-sm text-gray-500 mt-1 max-w-2xl">Record goods returned against a completed invoice. Review stock, balance adjustments and refund records, then download the document.</p>
    </div>

    <form method="GET" action="{{ route('manage.workspace.documents', ['page' => 'credit-notes']) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-4">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search note number, invoice, customer or item" class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-[#f8f9fa] focus:ring-0 outline-none transition text-lg">
        </div>
    </form>

    <div class="mb-6">
        <a href="{{ route('manage.workspace.documents', ['page' => 'issue-credit-note']) }}" class="inline-flex bg-[#713FCE] hover:bg-[#5a2ea6] text-white px-6 py-3 rounded-xl font-medium items-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Issue credit note
        </a>
    </div>

    <div class="space-y-3">
        @forelse($records as $record)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between cursor-pointer hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-[#713FCE] bg-[#f4effc]">
                        <i class="fa-solid fa-file-shield text-xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">CN-{{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-500">Invoice #{{ $record->sale_id }} • {{ trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?: 'Walk-in customer' }}</p>
                        <p class="text-sm text-gray-500">{{ $record->item_name }} • {{ \Carbon\Carbon::parse($record->returned_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">KES</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($record->amount_refunded, 2) }}</p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">No credit notes found.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection