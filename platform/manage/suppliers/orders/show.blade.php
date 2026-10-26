@extends('manage.layouts.workspace')
@php
    $name = trim($order->first_name . ' ' . ($order->last_name ?? ''));
    $done = $order->received_at !== null || strtolower((string) $order->status) === 'received';
    $canReceive = !$done && auth()->user()?->hasPermission('pos.suppliers.manage');
    $n = fn ($v) => rtrim(rtrim(number_format((float) $v, 4, '.', ''), '0'), '.');
@endphp
@section('title', 'Order #' . $order->id)
@section('heading', 'Order: ' . $name)
@section('subtitle', 'Order #' . $order->id)

@section('content')
<div class="swo-detail" x-data="{ toast: {{ session('status') ? 'true' : 'false' }}, busy: false }" x-init="if (toast) setTimeout(() => toast = false, 4500)">
    <div class="swo-chip-wrap"><span class="swo-chip {{ $done ? 'is-done' : '' }}">{{ $done ? 'Received' : 'Pending' }}</span></div>

    @if($errors->any())<p class="sw-md-error swo-err">{{ $errors->first() }}</p>@endif

    <form class="swo-form" method="POST" action="{{ route('manage.suppliers.orders.receive', $order->id) }}" @submit="busy = true">
        @csrf
        <div class="swo-cards">
            @foreach($items as $item)
                @php
                    $ordered   = (float) $item->quantity_ordered;
                    $got       = (float) $item->quantity_received;
                    $remaining = max(0, $ordered - $got);
                @endphp
                @if($canReceive && $remaining > 0)
                    <div class="swo-card">
                        <div class="swo-card-text">
                            <b>{{ $item->name }}</b>
                            <small>Ordered {{ $n($ordered) }} at {{ $n($item->unit_cost) }} each</small>
                            @if($got > 0)<small class="is-warn">Received {{ $n($got) }} so far</small>@endif
                        </div>
                        <div class="swo-recv">
                            <input id="recv-{{ $item->id }}" type="number" name="receive[{{ $item->id }}]" min="0" max="{{ $n($remaining) }}" step="any" inputmode="decimal" value="{{ $n($remaining) }}">
                            <label for="recv-{{ $item->id }}">Receive qty</label>
                        </div>
                    </div>
                @else
                    <a class="swo-card" href="{{ route('manage.inventory.product.show', $item->product_id) }}">
                        <div class="swo-card-text">
                            <b>{{ $item->name }}</b>
                            <small>Ordered {{ $n($ordered) }} at {{ $n($item->unit_cost) }} each</small>
                            @if($got >= $ordered && $got > 0)
                                <small class="is-ok">Received {{ $n($got) }} in full</small>
                            @elseif($got > 0)
                                <small class="is-warn">Received {{ $n($got) }} of {{ $n($ordered) }}</small>
                            @endif
                        </div>
                        <i class="fa-solid fa-chevron-right sw-chevron" aria-hidden="true"></i>
                    </a>
                @endif
            @endforeach
        </div>

        @if($canReceive)
            <button type="submit" class="swo-receive" :disabled="busy"><i class="fa-solid fa-box-archive"></i> Receive order</button>
        @endif
    </form>

    <div class="swo-toast" x-cloak x-show="toast" x-transition.opacity role="status">{{ session('status') }}</div>
</div>
@endsection