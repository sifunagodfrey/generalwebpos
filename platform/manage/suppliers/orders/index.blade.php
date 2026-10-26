@extends('manage.layouts.workspace')
@php
    $isDeliveries = $page === 'deliveries';
    $title = $isDeliveries ? 'Pending deliveries' : 'Purchase orders';
@endphp
@section('title', $title)
@section('heading', $title)

@section('content')
@unless($isDeliveries)
    <form class="sw-search-card" action="{{ route('manage.suppliers.orders') }}" method="GET">
        <div class="sw-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input name="q" value="{{ $search }}" placeholder="Search purchase orders by supplier" aria-label="Search purchase orders by supplier">
        </div>
        @can_do('pos.suppliers.manage')
            <a class="swo-new" href="{{ route('manage.suppliers.orders.create') }}"><i class="fa-solid fa-plus"></i> New order</a>
        @endcan_do
    </form>
@endunless

<section class="sw-people-list" aria-label="{{ $title }}">
    @forelse($records as $row)
        @php
            $done = $row->received_at !== null || strtolower((string) $row->status) === 'received';
            $supplierName = trim($row->first_name . ' ' . ($row->last_name ?? ''));
        @endphp
        <a class="sw-person sw-person-row" href="{{ route('manage.suppliers.orders.show', $row->id) }}">
            <i class="{{ $done ? 'fa-solid fa-circle-check swo-ico swo-ico--ok' : 'fa-regular fa-clock swo-ico' . ($isDeliveries ? ' swo-ico--warn' : '') }}" aria-hidden="true"></i>
            <div>
                <strong>{{ $supplierName }}</strong>
                <small>Order #{{ $row->id }}@unless($isDeliveries) - {{ $done ? 'Received' : 'Pending' }}@endunless</small>
            </div>
            <i class="fa-solid fa-chevron-right sw-chevron" aria-hidden="true"></i>
        </a>
    @empty
        <div class="sw-empty swo-empty">
            @if($search !== '') No matching purchase orders found.
            @elseif($isDeliveries) No pending deliveries.
            @else No purchase orders yet.
            @endif
        </div>
    @endforelse
</section>

@if($records->hasPages())
<div class="sw-page-tools"><span>{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</span><nav class="sw-pager" aria-label="Order pages"><a @if($records->previousPageUrl()) href="{{ $records->previousPageUrl() }}" @else aria-disabled="true" @endif>Previous</a><a @if($records->nextPageUrl()) href="{{ $records->nextPageUrl() }}" @else aria-disabled="true" @endif>Next</a></nav></div>
@endif
@endsection