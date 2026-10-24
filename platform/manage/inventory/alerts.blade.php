@extends('manage.layouts.workspace')
@section('title', 'Product Alerts')
@section('heading', 'Product Alerts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swi-wrap">
    <div class="swia-header">
        <form method="GET" action="{{ route('manage.workspace.inventory', ['page' => 'alerts']) }}" class="swia-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input name="q" value="{{ $search }}" placeholder="Search product or variant" autocomplete="off">
            @if($tab !== 'all')
                <input type="hidden" name="tab" value="{{ $tab }}">
            @endif
        </form>
        <div class="swia-tabs">
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => 'low', 'q' => $search]) }}" class="swia-tab {{ $tab === 'low' ? 'active' : '' }}">
                Low stock <span class="swia-count">{{ $lowStockCount }}</span>
            </a>
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => 'out', 'q' => $search]) }}" class="swia-tab {{ $tab === 'out' ? 'active' : '' }}">
                Out of stock <span class="swia-count">{{ $outOfStockCount }}</span>
            </a>
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => 'expires', 'q' => $search]) }}" class="swia-tab {{ $tab === 'expires' ? 'active' : '' }}">
                Expires soon <span class="swia-count">{{ $expiresSoonCount }}</span>
            </a>
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => 'expired', 'q' => $search]) }}" class="swia-tab {{ $tab === 'expired' ? 'active' : '' }}">
                Expired <span class="swia-count">{{ $expiredCount }}</span>
            </a>
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => 'all', 'q' => $search]) }}" class="swia-tab {{ $tab === 'all' ? 'active' : '' }}">
                @if($tab === 'all') <i class="fa-solid fa-check"></i> @endif All alerts <span class="swia-count">{{ $allCount }}</span>
            </a>
        </div>
    </div>

    <div class="swia-list">
        @forelse($records as $record)
            <a href="{{ route('manage.inventory.product.show', $record->id) }}" class="swia-row">
                <span>{{ $record->name }}</span>
                <strong>{{ number_format($record->stock, 0) }} in stock</strong>
            </a>
        @empty
            <div class="swia-empty">No matching stock alerts.</div>
        @endforelse
    </div>

    <div class="swia-footer">
        <span>{{ $records->total() }} alerts</span>
        <div class="swia-pager">
            @if($records->previousPageUrl())
                <a href="{{ $records->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a>
            @else
                <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @endif
            
            @if($records->nextPageUrl())
                <a href="{{ $records->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
            
            <a href="{{ route('manage.workspace.inventory', ['page' => 'alerts', 'tab' => $tab]) }}" class="refresh"><i class="fa-solid fa-rotate-right"></i></a>
        </div>
    </div>
</div>
@endsection