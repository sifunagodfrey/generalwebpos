<!doctype html>
<html lang="en" x-data="{navOpen:false,profileOpen:false,moreOpen:false,saleTab:'products',shiftModal:false,customerModal:false}" @sw:sale-tab.window="saleTab=$event.detail" @sw:open-shift.window="shiftModal=true" @sw:open-customer.window="customerModal=true" @keydown.escape.window="moreOpen=false;profileOpen=false">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Suweka POS') — Suweka POS</title>
<link rel="icon" href="{{ asset('images/suweka-icon.png') }}">
@vite(['resources/css/app.css','resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('css/suweka-workspace.css') }}">
@stack('styles')
</head>
<body class="sw-body">
@if(!empty($module))
<aside class="sw-sidebar" :class="{'sw-sidebar-open':navOpen}">
 <div class="sw-side-head"><a href="{{ route('manage.dashboard.index') }}" aria-label="Return to main menu"><i class="fa-solid fa-arrow-left"></i></a><strong>{{ $modules[$module]['label'] }}</strong><a class="sw-side-home" href="{{ route('manage.dashboard.index') }}" title="Main menu"><i class="fa-solid fa-house"></i></a></div>
 <nav class="sw-side-menu" aria-label="{{ $modules[$module]['label'] }} navigation">
 @foreach($modules[$module]['items'] as $nav)
 <a class="sw-nav-link {{ $page===$nav[0]?'active':'' }}" href="{{ route('manage.workspace.'.$module,['page'=>$nav[0]]) }}" @if($module==='customers' && ($nav[0]==='add' || strcasecmp($nav[1],'Add Customer')===0)) @click.prevent="customerModal=true;navOpen=false" @endif>
    <i class="fa-solid fa-{{ $nav[2] }}"></i>
    <span>{{ $nav[1] }}@if($nav[0]==='held' && isset($heldSalesCount)) ({{ $heldSalesCount }})@endif</span>
    @if($module === 'inventory' && $nav[0] === 'alerts')
        <span class="sw-nav-badge">{{ $alertsCount ?? 0 }}</span>
    @endif
 </a>
 @endforeach
 </nav>
 <div class="sw-side-bottom"><a href="{{ route('manage.workspace.settings',['page'=>'vat']) }}"><i class="fa-solid fa-gear"></i> Settings</a><form method="POST" action="{{ route('manage.auth.logout') }}">@csrf<button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Sign out</button></form></div>
</aside>
@endif
<div class="sw-shell {{ empty($module)?'sw-shell-home':'' }}">
<header class="sw-mobile-appbar" data-module="{{ $module ?? '' }}" aria-label="Mobile module header">
 <a class="sw-mobile-back" href="{{ route('manage.dashboard.index') }}" aria-label="Back" @click="if(saleTab==='cart'){$event.preventDefault();$dispatch('sw:close-cart')}"><i class="fa-solid fa-arrow-left"></i></a>
 <strong x-show="saleTab!=='cart'">@yield('heading', !empty($module) ? $modules[$module]['label'] : 'Suweka')</strong>
 @if(($module??'')==='sale')<strong x-cloak x-show="saleTab==='cart'">Cart</strong>@endif
 <span class="sw-mobile-appbar-actions">
@if(($module??'')==='sale')
<a class="sw-mobile-shift-dot {{ ($activeShift ?? null) ? 'is-open' : 'is-closed' }}" href="{{ route('manage.workspace.cashbox',['page'=>'index']) }}" @unless($activeShift ?? null) @click.prevent="shiftModal=true" @endunless title="{{ ($activeShift ?? null) ? 'Close shift' : 'Open shift' }}"><i class="fa-regular {{ ($activeShift ?? null) ? 'fa-circle-stop' : 'fa-circle-play' }}"></i></a>
@endif
 <a href="{{ route('manage.dashboard.index') }}" title="Main menu"><i class="fa-solid fa-house"></i></a>
 </span>
 </header>
 @if(!empty($module))
<nav class="sw-mobile-submenu" x-show="saleTab!=='cart'" aria-label="{{ $modules[$module]['label'] }} sections">
 @foreach($modules[$module]['items'] as $nav)
 <a href="{{ route('manage.workspace.'.$module,['page'=>$nav[0]]) }}" class="{{ $page===$nav[0]?'active':'' }}" @if($module==='customers' && ($nav[0]==='add' || strcasecmp($nav[1],'Add Customer')===0)) @click.prevent="customerModal=true" @endif><i class="fa-solid fa-{{ $nav[2] }}"></i> {{ $nav[1] }}</a>
 @endforeach
 </nav>@endif
 <header class="sw-topbar">
@if(!empty($module))<div class="sw-page-heading"><button class="sw-hamburger" @click="navOpen=!navOpen"><i class="fa-solid fa-bars"></i></button><div><h1>@yield('heading','Overview')</h1>@hasSection('subtitle')<p>@yield('subtitle')</p>@endif</div></div>
@else <a href="{{ route('manage.dashboard.index') }}" class="sw-logo"><img src="{{ asset('images/suweka-logo.png') }}" alt="Suweka" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'"> <span style="display:none">Suweka</span></a>@endif
@if(($module??'')==='sale')
<a class="sw-shift-pill {{ ($activeShift ?? null) ? 'is-open' : 'is-closed' }}" href="{{ route('manage.workspace.cashbox',['page'=>'index']) }}" @unless($activeShift ?? null) @click.prevent="shiftModal=true" @endunless><span class="sw-shift-dot"></span>{{ ($activeShift ?? null) ? 'Close shift' : 'Open shift' }}</a>
@endif
<div class="sw-userbar" @click.outside="profileOpen=false"><div class="sw-avatar">{{ strtoupper(substr(auth()->user()->first_name??'U',0,1).substr(auth()->user()->last_name??'',0,1)) }}</div><span>{{ trim((auth()->user()->first_name??'').' '.(auth()->user()->last_name??'')) }}</span><button type="button" @click.stop="profileOpen=!profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" aria-label="Account options"><i class="fa-solid fa-angle-down"></i></button><div class="sw-account-pop" x-cloak x-show="profileOpen" x-transition.opacity><form method="POST" action="{{ route('manage.auth.logout') }}">@csrf<button type="submit">Sign out</button></form></div></div>
</header>
<main class="sw-content">@yield('content')</main>
 <nav class="sw-mobile-bottom-nav" aria-label="Main POS navigation">
 <a href="{{ route('manage.workspace.sale',['page'=>'new']) }}" :class="{'active': saleTab==='cart'}" data-sale-cart-nav><span class="sw-nav-symbol"><i class="fa-solid fa-cart-shopping"></i><b class="sw-bottom-count" hidden>0</b></span><span>Cart</span></a>
 <a href="{{ route('manage.workspace.sale',['page'=>'new']) }}" :class="{'active': saleTab!=='cart' && {{ ($module??'')==='sale' ? 'true' : 'false' }}}" @click="if(saleTab==='cart'){$event.preventDefault();$dispatch('sw:close-cart')}"><i class="fa-solid fa-cash-register"></i><span>Sale</span></a>
 <a href="{{ route('manage.workspace.cashbox',['page'=>'index']) }}" class="{{ ($module??'')==='cashbox'?'active':'' }}"><i class="fa-solid fa-wallet"></i><span>Cash Box</span></a>
 <a href="{{ route('manage.workspace.inventory',['page'=>'products']) }}" class="{{ ($module??'')==='inventory'?'active':'' }}"><i class="fa-solid fa-box-archive"></i><span>Inventory</span></a>
 <button type="button" class="sw-more-toggle" @click="moreOpen=true" aria-label="More modules"><i class="fa-solid fa-ellipsis"></i><span>More</span></button>
 </nav>
 <div x-cloak x-show="moreOpen" @keydown.escape.window="moreOpen=false" style="position:fixed;inset:0;z-index:150;background:#16102380" @click.self="moreOpen=false">
  <div style="position:absolute;bottom:0;left:0;right:0;max-height:82vh;overflow-y:auto;background:#fff;border-radius:18px 18px 0 0;padding:20px 20px 90px">
   <div style="display:flex;justify-content:space-between;align-items:center"><h3>More modules</h3><button @click="moreOpen=false" aria-label="Close more modules"><i class="fa-solid fa-xmark"></i></button></div>
   <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:16px">
   @foreach(['dashboard'=>'Dashboard','customers'=>'Customers','suppliers'=>'Suppliers','staff'=>'Staff','documents'=>'Documents','reports'=>'Reports','settings'=>'Settings'] as $key=>$title)
    <a style="padding:15px;background:#f5f0fc;border:1px solid #e4d8fa;border-radius:10px;color:#713FCE;font-weight:600" href="{{route('manage.workspace.'.$key,['page'=> config('suweka_ui.'.$key.'.items.0.0','index')])}}">{{$title}}</a>
   @endforeach

   <a style="padding:15px;background:#f5f0fc;border:1px solid #e4d8fa;border-radius:10px;color:#713FCE" href="{{route('manage.dashboard.index')}}">Main menu</a>
   </div>
  </div>
 </div>
</div>
@if(($module??'')==='sale' && !($activeShift ?? null))
@include('manage.layouts.partials.open-shift-modal')
@endif
@if(($module??'')==='customers')
@include('manage.layouts.partials.add-customer-modal')
@endif
<script src="{{ asset('js/suweka-workspace.js') }}" defer></script>
</body></html>