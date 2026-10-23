@extends('manage.layouts.workspace')
@section('title','Held sale #'.$sale->id)
@section('heading',$sale->hold_label ?: 'Held sale #'.$sale->id)
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div class="sws-detail">
 <div class="sws-detail-actions"><a href="{{ route('manage.sales.held') }}" class="sws-link">← Held sales</a><a class="sws-btn sws-primary" href="{{ route('manage.workspace.sale',['page'=>'new','recall'=>$sale->id]) }}"><i class="fa-regular fa-circle-play"></i> Recall</a>
 <form method="POST" action="{{ route('manage.sales.discard',$sale->id) }}" onsubmit="return confirm('Delete this held sale?')">@csrf @method('DELETE')<button class="sws-btn" type="submit">Delete</button></form></div>
 <div class="sws-card"><h3>Items</h3>@foreach($lines as $line)<div class="sws-line"><span>{{ $line->product_name ?: 'Product #'.$line->product_id }}{{ $line->variant_name ? ' — '.$line->variant_name : '' }}<small>{{ $line->quantity }} × {{ number_format($line->unit_price,2) }}</small></span><strong>{{ number_format($line->line_total,2) }}</strong></div>@endforeach</div>
 <div class="sws-card"><div class="sws-line"><b>Total</b><strong>{{ number_format($sale->total,2) }}</strong></div></div>
</div>
@endsection
