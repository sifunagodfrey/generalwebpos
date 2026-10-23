@extends('manage.layouts.workspace')
@section('title','Held sales')
@section('heading','Held sales')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<section class="sws-list-page">
 <strong class="sws-caption">Held sales ({{ $records->total() }})</strong>
 @forelse($records as $sale)
 <a class="sws-list-row" href="{{ route('manage.sales.held.detail',$sale->id) }}">
  <span class="sws-icon"><i class="fa-regular fa-circle-pause"></i></span><span class="sws-list-copy"><b>{{ $sale->hold_label ?: 'Held sale #'.$sale->id }}</b><small>{{ $sale->held_at }}</small></span><strong>{{ number_format($sale->total,0) }}</strong><i class="fa-solid fa-chevron-right sws-chevron"></i>
 </a>
 @empty <div class="sws-empty">No held sales.</div> @endforelse
 {{ $records->links() }}
</section>
@endsection
