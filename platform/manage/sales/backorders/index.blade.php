@extends('manage.layouts.workspace')
@section('title','Backorders')
@section('heading','Backorders')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div x-data="{showForm:false}" class="sws-list-page">
 <section class="sws-card">
  <div class="sws-segment"><a class="{{ $mode==='backorders'?'selected':'' }}" href="{{ route('manage.sales.backorders') }}">✓ &nbsp; Backorders</a><a class="{{ $mode==='missing'?'selected':'' }}" href="{{ route('manage.sales.backorders',['tab'=>'missing']) }}">Missing stock</a></div>
  <form method="GET" action="{{ route('manage.sales.backorders') }}" class="sws-search-field"><input type="hidden" name="tab" value="{{ $mode }}"><i class="fa-solid fa-magnifying-glass"></i><input name="q" value="{{ $search }}" placeholder="Search {{ $mode==='missing'?'missing stock':'backorders' }} by product"></form>
  <button type="button" class="sws-btn sws-primary" @click="showForm=!showForm"><i class="fa-solid fa-plus"></i> Add backorder</button>
  <form x-show="showForm" x-cloak class="sws-form" method="POST" action="{{ route('manage.sales.missing.store') }}">@csrf
   <p>Record a missing stock request without charging a sale.</p><label>Product requested <input required maxlength="250" name="product_name" value="{{ old('product_name') }}"></label><label>Notes <textarea name="note" maxlength="1500">{{ old('note') }}</textarea></label><button class="sws-btn sws-primary" type="submit">Save request</button>
  </form>
 </section>
 @if(session('status'))<p class="sws-flash">{{ session('status') }}</p>@endif
 @error('product_name')<p class="sws-error">{{ $message }}</p>@enderror
 @forelse($records as $record)
  <div class="sws-list-row"><span class="sws-icon"><i class="fa-solid fa-truck-fast"></i></span><span class="sws-list-copy"><b>{{ $mode==='missing' ? $record->product_name : ($record->product_name ?: 'Product #'.$record->product_id) }}</b><small>{{ $record->created_at }}</small></span>@if($mode==='backorders')<strong>{{ number_format($record->quantity_pending,2) }} pending</strong>@endif</div>
 @empty <div class="sws-empty">{{ $mode==='missing'?'No missing stock requests.':'No pending backorders.' }}</div>@endforelse
 {{ $records->links() }}
</div>
@endsection
