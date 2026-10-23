@extends('manage.layouts.workspace')
@section('title','Backorders')
@section('heading','Backorders')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div x-data="{showForm: {{ $errors->any() ? 'true' : 'false' }}}" class="sws-list-page sws-backorders">
 <section class="sws-card">
  <div class="sws-segment">
   <a class="{{ $mode==='backorders'?'selected':'' }}" href="{{ route('manage.sales.backorders') }}">@if($mode==='backorders')<i class="fa-solid fa-check"></i>@endif<span>Backorders</span></a>
   <a class="{{ $mode==='missing'?'selected':'' }}" href="{{ route('manage.sales.backorders',['tab'=>'missing']) }}">@if($mode==='missing')<i class="fa-solid fa-check"></i>@endif<span>Missing stock</span></a>
  </div>
  <form method="GET" action="{{ route('manage.sales.backorders') }}" class="sws-search-field"><input type="hidden" name="tab" value="{{ $mode }}"><i class="fa-solid fa-magnifying-glass"></i><input name="q" value="{{ $search }}" placeholder="{{ $mode==='missing' ? 'Search missing stock requests' : 'Search backorders by product' }}"></form>
  {{-- --------------- --}}
  {{-- Both tabs open the same dialog --}}
  {{-- --------------- --}}
  <button type="button" class="sws-btn sws-primary" @click="showForm=true"><i class="fa-solid fa-plus"></i> {{ $mode==='missing' ? 'Add request' : 'Add backorder' }}</button>
 </section>
 @if(session('status'))<p class="sws-flash">{{ session('status') }}</p>@endif
 @if($mode==='missing')
  @if($records->count())
   <div class="sws-requests">
    @foreach($records as $record)
     <div class="sws-request">
      <i class="fa-regular fa-circle-question sws-request-icon" aria-hidden="true"></i>
      <div class="sws-request-copy">
       <b>{{ $record->product_name }}</b>
       <span>{!! nl2br(e($record->note)) !!}</span>
       <small>{{ \Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d H:i:s.v') }}</small>
      </div>
     </div>
    @endforeach
   </div>
  @else
   <div class="sws-empty">No missing stock requests.</div>
  @endif
 @else
  @forelse($records as $record)
   <div class="sws-list-row"><span class="sws-icon"><i class="fa-solid fa-truck-fast"></i></span><span class="sws-list-copy"><b>{{ $record->product_name ?: 'Product #'.$record->product_id }}</b><small>{{ $record->created_at }}</small></span><strong>{{ number_format($record->quantity_pending,2) }} pending</strong></div>
  @empty <div class="sws-empty">No pending backorders.</div>@endforelse
 @endif
 {{ $records->links() }}
 @include('manage.sales.backorders.partials.request-modal')
</div>
@endsection