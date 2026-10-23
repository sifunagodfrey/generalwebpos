@extends('manage.layouts.workspace')
@section('title',$product ? 'Edit product' : 'New product')
@section('heading',$product ? 'Edit product' : 'New product')
@section('subtitle','Product details')
@section('content')
<link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}">
<div class="swi-wrap swi-narrow"><a class="swi-back" href="{{ $product ? route('manage.inventory.product.show',$product->id) : route('manage.workspace.inventory',['page'=>'products']) }}">← Back</a>
  <form class="swi-form" method="POST" action="{{ $product ? route('manage.inventory.product.update',$product->id) : route('manage.inventory.product.store') }}">
    @csrf @if($product) @method('PUT') @endif
    @if($errors->any())<div class="swi-error">{{ $errors->first() }}</div>@endif
    <label>Product name<input name="name" required maxlength="150" value="{{ old('name',$product->name ?? '') }}"></label>
    <div class="swi-form-columns">
      <label>Category<select name="category_id"><option value="">None</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$product->category_id ?? '')==$category->id)>{{ $category->name }}</option>@endforeach</select></label>
      <label>Brand<select name="brand_id"><option value="">None</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id',$product->brand_id ?? '')==$brand->id)>{{ $brand->name }}</option>@endforeach</select></label>
      <label>Unit<select name="unit_of_measure_id"><option value="">None</option>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_of_measure_id',$product->unit_of_measure_id ?? '')==$unit->id)>{{ $unit->name }}</option>@endforeach</select></label>
      <label>Tax type<select name="tax_type">@foreach(['standard_rated'=>'Standard rated','zero_rated'=>'Zero rated','exempt'=>'Exempt'] as $key=>$label)<option value="{{ $key }}" @selected(old('tax_type',$product->tax_type ?? 'standard_rated')===$key)>{{ $label }}</option>@endforeach</select></label>
      <label>Cost price<input type="number" step="0.01" min="0" name="cost_price" required value="{{ old('cost_price',$product->cost_price ?? 0) }}"></label>
      <label>Selling price<input type="number" step="0.01" min="0" name="selling_price" required value="{{ old('selling_price',$product->selling_price ?? 0) }}"></label>
      <label>Discount price<input type="number" step="0.01" min="0" name="discount_price" value="{{ old('discount_price',$product->discount_price ?? '') }}"></label>
    </div>
    <label>Description<textarea name="description" rows="3">{{ old('description',$product->description ?? '') }}</textarea></label>
    <label class="swi-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->is_active ?? true))> Active product</label>
    <button class="swi-primary" type="submit">{{ $product ? 'Save changes' : 'Create product' }}</button>
  </form>
</div>
@endsection
