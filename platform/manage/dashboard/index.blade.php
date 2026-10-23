@extends('manage.layouts.workspace')
@section('title','Main menu')
@section('content')
<div class="sw-home-grid">
@php $home=config('suweka_ui');$counts=$counts??[];$accent=['sale'=>'purple','dashboard'=>'purple','inventory'=>'blue','customers'=>'pink','suppliers'=>'green','staff'=>'orange','cashbox'=>'blue','documents'=>'pink','reports'=>'teal','settings'=>'slate']; @endphp
@foreach($home as $slug=>$mod)
@php $start=$mod['items'][0][0];$start=($slug==='settings'?'vat':$start); @endphp
<a class="sw-home-card" href="{{ route('manage.workspace.'.$slug,['page'=>$start]) }}">
  <span class="sw-tile-icon sw-color-{{ $accent[$slug] }}"><i class="fa-solid fa-{{ $mod['icon'] }}"></i></span>
  <strong>{{ $mod['label'] }}</strong><small>{{ $mod['desc'] }}</small>
  <div class="sw-card-footer"><span>@if(isset($counts[$slug]))<b class="sw-count">{{ $counts[$slug] }}</b><small>{{ $mod['label']==='Inventory'?'Products':($mod['label']==='Staff'?'Staff Members':$mod['label']) }}</small>@else<span class="sw-card-action">{{ $mod['action']??'View '. $mod['label'] }}</span>@endif</span><span class="sw-card-arrow sw-color-{{ $accent[$slug] }}"><i class="fa-solid fa-arrow-right"></i></span></div>
</a>
@endforeach
</div>
@endsection
