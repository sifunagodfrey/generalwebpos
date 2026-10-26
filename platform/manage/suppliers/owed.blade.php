@extends('manage.layouts.workspace')
@section('title', 'Payments owed')
@section('heading', 'Payments owed')

@section('content')
<section class="sw-people-list" aria-label="Payments owed">
    @forelse($records as $row)
        <a class="sw-person sw-person-row" href="{{ route('manage.suppliers.show', $row->id) }}">
            <i class="fa-solid fa-truck" aria-hidden="true"></i>
            <div>
                <strong>{{ trim($row->first_name . ' ' . ($row->last_name ?? '')) }}</strong>
                <small>{{ $row->phone ?? '' }}</small>
            </div>
            <span class="swo-owed"><small>Owed</small><b>{{ number_format((float) $row->balance_owed, 0) }}</b></span>
            <i class="fa-solid fa-chevron-right sw-chevron" aria-hidden="true"></i>
        </a>
    @empty
        <div class="sw-empty swo-empty">No payments owed.</div>
    @endforelse
</section>

@if($records->hasPages())
<div class="sw-page-tools"><span>{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</span><nav class="sw-pager" aria-label="Payments owed pages"><a @if($records->previousPageUrl()) href="{{ $records->previousPageUrl() }}" @else aria-disabled="true" @endif>Previous</a><a @if($records->nextPageUrl()) href="{{ $records->nextPageUrl() }}" @else aria-disabled="true" @endif>Next</a></nav></div>
@endif
@endsection