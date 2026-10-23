@extends('manage.layouts.workspace')
@section('title','Products')
@section('heading','Products')
@section('subtitle','Products, stock and backorders')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}">
<div class="swi-wrap">
  @if(session('status'))<div class="swi-success">{{ session('status') }}</div>@endif
  <form method="GET" class="swi-search" action="{{ route('manage.workspace.inventory',['page'=>'products']) }}">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input name="q" value="{{ $search }}" placeholder="Search products" aria-label="Search products" data-swi-search autocomplete="off">
    <a href="{{ route('manage.workspace.inventory',['page'=>'create']) }}" class="swi-add" title="New product"><i class="fa-solid fa-plus"></i><span>New product</span></a>
  </form>
  <div class="swi-catalog" id="swi-results">
    <div class="swi-grid">
      @forelse($products as $product)
        <a class="swi-card" href="{{ route('manage.inventory.product.show',$product->id) }}">
          <div class="swi-placeholder"><i class="fa-solid fa-qrcode"></i></div>
          <div class="swi-card-body"><strong title="{{ $product->name }}">{{ $product->name }}</strong><small>{{ $product->has_variants ? 'Has variants' : number_format($product->stock,0).' in stock' }}</small><b>{{ number_format($product->selling_price,0) }}</b></div>
        </a>
      @empty<div class="swi-empty">No matching products found. <a href="{{ route('manage.workspace.inventory',['page'=>'create']) }}">New product</a></div>@endforelse
    </div>
    <div class="swi-pagination"><span>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span><span>@if($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}">‹ Previous</a>@endif @if($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}">Next ›</a>@endif</span></div>
  </div>
</div>
<script src="{{ asset('js/suweka-inventory.js') }}" defer></script>
@endsection
