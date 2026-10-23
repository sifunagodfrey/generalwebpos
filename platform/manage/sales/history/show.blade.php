@extends('manage.layouts.workspace')
@section('title','Sale receipt')
@section('heading','Receipt #'.$sale->id)
@section('subtitle','Completed '.$sale->completed_at)
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-receipt.css') }}">
<div class="swr-page">
  <article id="swr-print" class="swr-receipt">
    <div class="swr-head"><img src="{{ asset('images/suweka-logo.png') }}" alt="Suweka" class="swr-logo"><h2>Sale receipt</h2><div>Receipt #{{ $sale->id }}</div><small>{{ $sale->completed_at }}</small></div>
    <div class="swr-rule"></div>
    <div class="swr-item swr-item-header"><span>Item</span><span>Qty</span><span>Amount</span></div>
    @foreach($lines as $line)
      <div class="swr-item"><span>{{ $line->name ?? ('Product #'.$line->product_id) }}<small>KES {{ number_format($line->unit_price,2) }} each</small></span><span>{{ number_format($line->quantity,2) }}</span><strong>{{ number_format($line->line_total,2) }}</strong></div>
    @endforeach
    <div class="swr-rule"></div>
    <div class="swr-sum"><span>Subtotal</span><span>KES {{ number_format($sale->subtotal,2) }}</span></div>
    @if($sale->discount_total > 0)<div class="swr-sum"><span>Discount</span><span>− KES {{ number_format($sale->discount_total,2) }}</span></div>@endif
    @if($sale->tax_total > 0)<div class="swr-sum"><span>VAT</span><span>KES {{ number_format($sale->tax_total,2) }}</span></div>@endif
    <div class="swr-sum swr-total"><strong>Total</strong><strong>KES {{ number_format($sale->total,2) }}</strong></div>
    <div class="swr-rule"></div>
    <h3>Payments</h3>
    @forelse($payments as $payment)<div class="swr-sum"><span>{{ ucfirst(str_replace('_',' ',$payment->method)) }} @if($payment->reference)<small>({{ $payment->reference }})</small>@endif</span><span>KES {{ number_format($payment->amount,2) }}</span></div>@empty<p>No payment recorded.</p>@endforelse
    <p class="swr-thanks">Thank you for shopping with us.</p>
  </article>
  <div class="swr-actions"><button class="swr-print-btn" type="button" onclick="window.print()"><i class="fa-solid fa-print"></i> Print receipt</button><a href="{{ route('manage.workspace.sale',['page'=>'new']) }}">New sale</a><a href="{{ route('manage.sales.history') }}">Sale history</a></div>
</div>
@endsection
