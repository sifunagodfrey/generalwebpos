@extends('manage.layouts.workspace')
@section('title','Product details')
@section('heading','Product details')
@section('subtitle',$record->name)
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}">
<div class="swi-wrap swi-narrow"><a class="swi-back" href="{{ route('manage.workspace.inventory',['page'=>'products']) }}">← Products</a>
  @if(session('status'))<div class="swi-success">{{ session('status') }}</div>@endif
  <article class="swi-detail"><div class="swi-detail-image"><i class="fa-solid fa-qrcode"></i></div><h2>{{ $record->name }}</h2><p>{{ $record->description }}</p>
  <div class="swi-kpis"><div>Price <strong>KES {{ number_format($record->selling_price,2) }}</strong></div><div>Stock on hand <strong>{{ number_format($stock,2) }}</strong></div><div>Status <strong>{{ $record->is_active ? 'Active' : 'Inactive' }}</strong></div></div>
  <a class="swi-primary" href="{{ route('manage.inventory.product.edit',$record->id) }}">Edit product</a></article>
  @if($variants->isNotEmpty())<section class="swi-detail"><h3>Variants</h3>@foreach($variants as $variant)<div class="swi-row"><span>{{ $variant->name }}</span><span>KES {{ number_format($variant->selling_price,2) }}</span></div>@endforeach</section>@endif
  <section class="swi-detail"><h3>Stock batches</h3>@forelse($batches as $batch)<div class="swi-row"><span>{{ number_format($batch->quantity,2) }} units @if($batch->variant_id) · Variant #{{ $batch->variant_id }}@endif</span><span>{{ $batch->expiry_date ? 'Expires '.$batch->expiry_date : 'No expiry' }}</span></div>@empty<p>No stock batches recorded.</p>@endforelse</section>
</div>
@endsection
