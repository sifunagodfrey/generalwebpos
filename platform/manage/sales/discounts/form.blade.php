@extends('manage.layouts.workspace')
@section('title',$discount?'Update discount':'New discount')
@section('heading',$discount?'Update discount':'New discount')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-sales-submodules.css') }}">
<div class="sws-detail"><a class="sws-link" href="{{ route('manage.sales.discounts') }}">← Discounts</a><form method="POST" class="sws-card sws-form" action="{{ $discount?route('manage.sales.discounts.update',$discount->id):route('manage.sales.discounts.store') }}">@csrf @if($discount)@method('PUT')@endif
<label>Product <select name="product_id" required><option value="">Choose product</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id',$discount->product_id??null)==$product->id)>{{ $product->name }}</option>@endforeach</select></label>
<label>Discount percent <input name="discount_percent" type="number" step="0.01" min="0" max="100" value="{{ old('discount_percent',$discount->discount_percent??'') }}"></label>
<label>Or discount amount <input name="discount_amount" type="number" step="0.01" min="0" value="{{ old('discount_amount',$discount->discount_amount??'') }}"></label>
<label>Starts at <input required name="starts_at" type="datetime-local" value="{{ old('starts_at',$discount?date('Y-m-d\TH:i',strtotime($discount->starts_at)):'') }}"></label>
<label>Ends at <input required name="ends_at" type="datetime-local" value="{{ old('ends_at',$discount?date('Y-m-d\TH:i',strtotime($discount->ends_at)):'') }}"></label>
@foreach($errors->all() as $error)<p class="sws-error">{{ $error }}</p>@endforeach
<button type="submit" class="sws-btn sws-primary">{{ $discount?'Update':'Save' }}</button></form>
@if($discount)<form action="{{ route('manage.sales.discounts.destroy',$discount->id) }}" method="POST" onsubmit="return confirm('Remove discount?')">@csrf @method('DELETE')<button class="sws-btn" type="submit">Remove discount</button></form>@endif</div>
@endsection
