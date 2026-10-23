@extends('manage.layouts.workspace')
@section('title','Returns')
@section('heading','Returns')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div class="sws-returns">
 <div class="sws-return-left"><form method="GET" action="{{ route('manage.sales.returns') }}" class="sws-search-field"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="{{ $search }}" placeholder="Search by sale # or customer"></form>
 @forelse($records as $sale)<a href="{{ route('manage.sales.returns.show',$sale->id) }}" class="sws-list-row"><i class="fa-solid fa-receipt sws-purple"></i><span class="sws-list-copy"><b>Sale #{{ $sale->id }}</b><small>{{ trim(($sale->first_name??'').' '.($sale->last_name??'')) ?: 'Walk-in customer' }}</small></span><strong>{{ number_format($sale->total,0) }}</strong></a>@empty<div class="sws-empty">No completed sales found.</div>@endforelse
 {{ $records->links() }}</div><div class="sws-return-right">Select a completed sale to process a return.</div>
</div>
@endsection
