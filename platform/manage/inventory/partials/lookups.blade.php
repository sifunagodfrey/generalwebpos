@extends('manage.layouts.workspace')
@section('title',ucfirst($type))
@section('heading',ucfirst($type))
@section('subtitle','Manage '. $type)
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}">
<div class="swi-wrap swi-narrow">
@if(session('status'))<div class="swi-success">{{ session('status') }}</div>@endif
@if($errors->any())<div class="swi-error">{{ $errors->first() }}</div>@endif
<form class="swi-search" method="GET"><i class="fa-solid fa-magnifying-glass"></i><input name="q" placeholder="Search {{ $type }}" value="{{ $search }}"></form>
<div class="swi-detail"><h3>Add {{ $type==='brands'?'brand':'category' }}</h3><form method="POST" action="{{ route('manage.inventory.lookup.store',['type'=>$type]) }}" class="swi-inline-form">@csrf<input name="name" required maxlength="100" placeholder="Name"><button class="swi-primary">Add</button></form></div>
@foreach($records as $record)<div class="swi-detail"><form method="POST" class="swi-inline-form" action="{{ route('manage.inventory.lookup.update',['type'=>$type,'id'=>$record->id]) }}">@csrf @method('PUT')<input name="name" value="{{ $record->name }}" required maxlength="100"><button class="swi-primary">Save</button></form></div>@endforeach
<div class="swi-pagination">{{ $records->links() }}</div>
</div>
@endsection
