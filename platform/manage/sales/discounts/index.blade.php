@extends('manage.layouts.workspace')
@section('title','Discounts')
@section('heading','Discounts')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<section class="sws-list-page" x-data="{showForm: {{ $errors->any() ? 'true' : 'false' }}}">
<div class="sws-card"><form class="sws-search-field" action="{{ route('manage.sales.discounts') }}"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="{{ $search }}" placeholder="Search discounts by product"></form><button type="button" class="sws-btn sws-primary w-[200px]" @click="showForm=true"><i class="fa-solid fa-plus"></i> New discount</button></div>
@if(session('status'))<p class="sws-flash">{{ session('status') }}</p>@endif
@forelse($records as $record)<a class="sws-list-row" href="{{ route('manage.sales.discounts.edit',$record->id) }}"><span class="sws-icon"><i class="fa-solid fa-percent"></i></span><span class="sws-list-copy"><b>{{ $record->product_name ?? 'Product' }}{{ $record->variant_name?' - '.$record->variant_name:'' }}</b><small>{{ $record->starts_at }} → {{ $record->ends_at }}</small></span><strong>{{ $record->discount_percent!==null ? number_format($record->discount_percent,0).'%' : number_format($record->discount_amount,0) }}</strong><i class="fa-solid fa-chevron-right sws-chevron"></i></a>@empty<div class="sws-empty">No matching discounts.</div>@endforelse
{{ $records->links() }}
@include('manage.sales.discounts.partials.discount-modal')
</section>
@endsection
