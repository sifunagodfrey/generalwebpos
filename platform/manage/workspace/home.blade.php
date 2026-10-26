@extends('manage.layouts.workspace')
@section('title', 'Main menu')

@section('content')
{{-- --------------- --}}
{{-- Main menu: one card per module --}}
{{-- --------------- --}}
@php
    // key => [title, icon, color, description, action link text, count label]
    $cards = [
        'sale'      => ['Sale',      'cash-register', 'purple', 'Process sales and receipts',  'Open Register',  null],
        'dashboard' => ['Dashboard', 'chart-column',  'purple', 'Overview and reports',        'View Insights',  null],
        'inventory' => ['Inventory', 'box-archive',   'blue',   'Manage products and stock',   null,             'Products'],
        'customers' => ['Customers', 'users',         'pink',   'Manage customer records',     null,             'Customers'],
        'suppliers' => ['Suppliers', 'truck',         'green',  'Manage supplier records',     null,             'Suppliers'],
        'staff'     => ['Staff',     'id-badge',      'orange', 'Manage staff accounts',       null,             'Staff Members'],
        'cashbox'   => ['Cash Box',  'wallet',        'blue',   'Manage cash in and out',      'View Cash Box',  null],
        'documents' => ['Documents', 'file-lines',    'pink',   'View and manage documents',   'View Documents', null],
        'reports'   => ['Reports',   'chart-simple',  'teal',   'Sales and shift reports',     'View Reports',   null],
        'settings'  => ['Settings',  'gear',          'slate',  'Shop and system settings',    'Open Settings',  null],
    ];
@endphp
<div class="sw-home-grid">
    @foreach($cards as $key => [$title, $icon, $color, $desc, $action, $countLabel])
        @continue(!isset($modules[$key]))
        <a class="sw-home-card sw-c-{{ $color }}" href="{{ route('manage.workspace.'.$key, ['page' => $modules[$key]['items'][0][0] ?? 'index']) }}">
            <span class="sw-tile-icon sw-color-{{ $color }}"><i class="fa-solid fa-{{ $icon }}"></i></span>
            <strong>{{ $title }}</strong>
            <small>{{ $desc }}</small>
            <span class="sw-card-footer">
                @if($countLabel)
                    <span><span class="sw-count">{{ number_format($counts[$key] ?? 0) }}</span><small>{{ $countLabel }}</small></span>
                @else
                    <span><span class="sw-card-action">{{ $action }}</span></span>
                @endif
                <span class="sw-card-arrow"><i class="fa-solid fa-arrow-right"></i></span>
            </span>
        </a>
    @endforeach
</div>
@endsection