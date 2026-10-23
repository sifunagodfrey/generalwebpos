@extends('manage.layouts.workspace')
@section('title',$heading)
@section('heading',$heading)
@section('subtitle',$description)
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales.css') }}">
<div class="sw-sale-list"><h3>{{ $heading }}</h3>@forelse($records as $record)<div class="sw-sale-record"><strong>#{{ $record->id }}</strong><small>{{ $record->created_at??'' }}</small></div>@empty<div class="sw-empty">Nothing to display.</div>@endforelse {{ $records->links() }}</div>
@endsection
