@extends('manage.layouts.workspace')
@section('title','Sale history')
@section('heading','Sale history')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div class="sws-history">
 <form method="GET" action="{{ route('manage.sales.history') }}" class="sws-search-card"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="{{ $search }}" placeholder="Search by receipt # or customer"></form>
 <form method="GET" action="{{ route('manage.sales.history') }}" class="sws-filter-actions"><input type="hidden" name="q" value="{{ $search }}"><label>From <input type="date" name="from" value="{{ $from }}"></label><label>To <input type="date" name="to" value="{{ $to }}"></label><button type="submit" class="sws-btn"><i class="fa-regular fa-calendar"></i> Select date range</button><a class="sws-btn" href="{{ route('manage.sales.history') }}">All dates</a><button class="sws-btn" type="button" onclick="window.print()"><i class="fa-solid fa-print"></i> Print list</button></form>
 <div class="sws-history-list">@forelse($records as $sale)<a class="sws-history-row" href="{{ route('manage.sales.receipt',$sale->id) }}"><i class="fa-solid fa-receipt sws-purple"></i><span><b>Receipt #{{ $sale->id }}</b><small>{{ trim(($sale->first_name??'').' '.($sale->last_name??'')) ?: 'Walk-in customer' }}</small></span><span class="sws-history-total">{{ number_format($sale->total,0) }}<small class="sws-paid">Paid</small></span><i class="fa-solid fa-chevron-right sws-chevron"></i></a>@empty<div class="sws-empty">No completed sales found.</div>@endforelse</div>
 {{ $records->links() }}
 </div>
@endsection
