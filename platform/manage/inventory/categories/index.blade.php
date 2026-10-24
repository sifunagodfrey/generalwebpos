@extends('manage.layouts.workspace')
@section('title', 'Categories')
@section('heading', 'Categories')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
<div class="swic-page">
    @if(session('status'))
        <div class="swi-success">{{ session('status') }}</div>
    @endif

    <div class="swic-top-card">
        <form method="GET" action="{{ route('manage.workspace.inventory', ['page' => 'categories']) }}" class="swic-search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search categories" autocomplete="off">
        </form>
        <a href="{{ route('manage.inventory.categories.create') }}" class="swic-btn-new">
            <i class="fa-solid fa-plus"></i> New category
        </a>
    </div>

    <div class="swic-grid">
        @forelse($categories as $category)
            <div class="swic-grid-card">
                <div class="swic-card-image">
                    @if($category->image_path)
                        <img src="{{ asset($category->image_path) }}" alt="{{ $category->name }}">
                    @else
                        <i class="fa-solid fa-shapes"></i>
                    @endif
                </div>
                <div class="swic-card-body">
                    <div class="swic-card-info">
                        <strong>{{ $category->name }}</strong>
                        <small>{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</small>
                    </div>
                    <div class="swic-card-actions">
                        <a href="{{ route('manage.inventory.categories.edit', $category->id) }}" title="Edit category">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        @include('manage.partials.delete', [
                            'action' => route('manage.inventory.categories.destroy', $category->id),
                            'confirm' => 'Delete the ' . $category->name . ' category?',
                            'class' => 'swic-delete-btn',
                        ])
                    </div>
                </div>
            </div>
        @empty
            <div class="swic-empty-state">No categories yet.</div>
        @endforelse
    </div>

    @if($categories->hasPages())
        <div class="swi-pagination mt-6">{{ $categories->links() }}</div>
    @endif
</div>
@endsection