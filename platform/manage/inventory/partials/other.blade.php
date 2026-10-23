@extends('manage.layouts.workspace')
@php $inventoryHeading = ['alerts'=>'Product Alerts','stocktake'=>'Stock take','backorders'=>'Backorders','reports'=>'Reports'][$page] ?? ucfirst($page); @endphp
@section('title',$inventoryHeading)
@section('heading',$inventoryHeading)
@section('subtitle','Inventory')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}">
<div class="swi-wrap"><div class="swi-detail">
@if($page==='alerts')
<h3>Low stock alerts</h3>
@forelse($records as $record)<a href="{{ route('manage.inventory.product.show',$record->id) }}" class="swi-row"><span>{{ $record->name }}</span><strong>{{ number_format($record->stock,2) }} in stock →</strong></a>@empty<p>No low-stock products.</p>@endforelse
@elseif($page==='backorders')
<h3>Backorders</h3>
@forelse($records as $record)<div class="swi-row"><span>Backorder #{{ $record->id }}</span><span>{{ $record->status ?? 'Pending' }}</span></div>@empty<p>No backorders recorded.</p>@endforelse
@elseif($page==='stocktake')
<h3>Stock take</h3><p>Stock counts and adjustment posting require a reconciled stock-take workflow. Use product details to review current stock batches.</p>
<a href="{{ route('manage.workspace.inventory',['page'=>'products']) }}" class="swi-primary">View products</a>
@elseif($page==='reports')
<h3>Product reports</h3><p>Browse product details and stock quantities. Sales, valuation and movement reports require a dedicated reporting service.</p>
<a href="{{ route('manage.workspace.inventory',['page'=>'products']) }}" class="swi-primary">View products</a>
@endif
</div></div>
@endsection
