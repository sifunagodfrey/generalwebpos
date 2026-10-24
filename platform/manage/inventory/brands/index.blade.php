@extends('manage.layouts.workspace')
@section('title', 'Brands')
@section('heading', 'Brands')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swic-page">
    @if(session('status'))
        <div class="swi-success">{{ session('status') }}</div>
    @endif

    <div class="swic-top-card">
        <form method="GET" action="{{ route('manage.workspace.inventory', ['page' => 'brands']) }}" class="swic-search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search brands" autocomplete="off">
        </form>
        <a href="{{ route('manage.inventory.brands.create') }}" class="swic-btn-new">
            <i class="fa-solid fa-plus"></i> New brand
        </a>
    </div>

    <div class="swic-grid">
        @forelse($brands as $brand)
            <div class="swic-grid-card">
                <div class="swic-card-image">
                    @if($brand->image_path)
                        <img src="{{ asset($brand->image_path) }}" alt="{{ $brand->name }}">
                    @else
                        <i class="fa-solid fa-tags"></i>
                    @endif
                </div>
                <div class="swic-card-body">
                    <div class="swic-card-info">
                        <strong>{{ $brand->name }}</strong>
                        <small>{{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</small>
                    </div>
                    <div class="swic-card-actions">
                        <a href="{{ route('manage.inventory.brands.edit', $brand->id) }}" title="Edit brand">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        @include('manage.partials.delete', [
                            'action' => route('manage.inventory.brands.destroy', $brand->id),
                            'confirm' => 'Delete the ' . $brand->name . ' brand?',
                            'class' => 'swic-delete-btn',
                        ])
                    </div>
                </div>
            </div>
        @empty
            <div class="swic-empty-state">No brands yet.</div>
        @endforelse
    </div>

    @if($brands->hasPages())
        <div class="swi-pagination mt-6">{{ $brands->links() }}</div>
    @endif
</div>
@endsection