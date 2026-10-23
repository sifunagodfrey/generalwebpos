@extends('manage.layouts.workspace')
@section('title','Return sale #'.$sale->id)
@section('heading','Returns')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div class="sws-detail"><a class="sws-link" href="{{ route('manage.sales.returns') }}">← Back to sales</a><div class="sws-card"><h3>Sale #{{ $sale->id }}</h3><p>Choose a sale item to review its returnable quantity.</p>
@foreach($lines as $line)
@php $remaining = max(0,(float)$line->quantity-(float)($alreadyReturned[$line->id]??0)); @endphp
<div class="sws-line"><span><b>{{ $line->product_name ?: 'Product #'.$line->product_id }}{{ $line->variant_name?' - '.$line->variant_name:'' }}</b><small>Sold {{ number_format($line->quantity,2) }} · Remaining {{ number_format($remaining,2) }}</small></span><span>{{ number_format($line->line_total,2) }}</span></div>
@endforeach
</div><div class="sws-card sws-muted">Issuing a credit note and refund requires the linked customer-credit, payment-allocation and restock service. It is not enabled in this build.</div></div>
@endsection
