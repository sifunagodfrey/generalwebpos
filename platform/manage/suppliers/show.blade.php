@extends('manage.layouts.workspace')
@php
    $name = trim($supplier->first_name . ' ' . ($supplier->last_name ?? ''));
    $paymentTypes = ['payment', 'repayment'];
    $ordersUrl = route('manage.suppliers.orders.create', ['supplier' => $supplier->id]);
@endphp
@section('title', $name)
@section('heading', $name)
@section('mobile_heading', 'Supplier details')
@section('subtitle', 'Supplier details')

{{-- --------------- --}}
{{-- Desktop actions, rendered in the top bar --}}
{{-- --------------- --}}
@section('topbar_actions')
    @can_do('pos.suppliers.manage')
        <button type="button" class="sws-btn sws-btn--outline" @click="$dispatch('sw:edit-supplier')"><i class="fa-solid fa-pencil"></i> Edit</button>
    @endcan_do
    <a class="sws-btn sws-btn--primary" href="{{ $ordersUrl }}"><i class="fa-solid fa-cart-plus"></i> Place order</a>
@endsection

@section('content')
<div class="sws" x-data="{ editModal: false, payModal: @js(old('_modal') === 'payment') }" @sw:edit-supplier.window="editModal=true" @keydown.escape.window="payModal=false">
    <div class="sws-col">
        <section class="sws-card">
            @if(session('status'))<p class="sws-flash">{{ session('status') }}</p>@endif
            @if($errors->any() && !old('_modal'))<p class="sws-flash sws-flash--error">{{ $errors->first() }}</p>@endif
            <h2>{{ $name }}</h2>
            <div class="sws-row"><span>Phone</span><b>{{ $supplier->phone }}</b></div>
            @if($supplier->address)<div class="sws-row"><span>Address</span><b>{{ $supplier->address }}</b></div>@endif
            @if($supplier->other_details)<div class="sws-row"><span>Details</span><b>{{ $supplier->other_details }}</b></div>@endif
        </section>

        {{-- --------------- --}}
        {{-- Mobile actions, hidden on desktop where they live in the top bar --}}
        {{-- --------------- --}}
        <div class="sws-mobile-actions">
            @can_do('pos.suppliers.manage')
                <button type="button" class="sws-btn sws-btn--outline" @click="editModal=true"><i class="fa-solid fa-pencil"></i> Edit supplier</button>
            @endcan_do
            <a class="sws-btn sws-btn--primary" href="{{ $ordersUrl }}"><i class="fa-solid fa-cart-plus"></i> Place order</a>
        </div>
        <section class="sws-card">
            <h3>Products from this supplier</h3>
            @forelse($products as $product)
                <div class="sws-item"><span class="sws-item-main"><b>{{ $product->name }}</b><small>Last cost {{ rtrim(rtrim(number_format((float) $product->cost, 2, '.', ''), '0'), '.') }}</small></span></div>
            @empty
                <p class="sws-empty">Nothing ordered from this supplier yet.</p>
            @endforelse
        </section>

        <section class="sws-card">
            <h3>Order history</h3>
            @forelse($orders as $order)
                @php $orderDone = $order->received_at !== null || strtolower((string) $order->status) === 'received'; @endphp
                <a class="sws-item" href="{{ route('manage.suppliers.orders.show', $order->id) }}">
                    <i class="{{ $orderDone ? 'fa-solid fa-circle-check is-ok' : 'fa-regular fa-clock' }}" aria-hidden="true"></i>
                    <span class="sws-item-main"><b>Order #{{ $order->id }}</b><small>{{ $orderDone ? 'Received' : 'Pending' }}</small></span>
                </a>
            @empty
                <p class="sws-empty">No orders placed with this supplier yet.</p>
            @endforelse
        </section>
    </div>

    <div class="sws-col">
        <section class="sws-card">
            <h3>Pending requests</h3>
            <p class="sws-sub">Orders waiting on supply</p>
            @forelse($pending as $order)
                <a class="sws-item" href="{{ route('manage.suppliers.orders.show', $order->id) }}">
                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                    <span class="sws-item-main"><b>Order #{{ $order->id }}</b><small>Pending</small></span>
                </a>
            @empty
                <p class="sws-empty">Nothing pending right now.</p>
            @endforelse
        </section>

        <section class="sws-card">
            <h3>Money owed</h3>
            <p class="sws-sub">For goods already received</p>
            <p class="sws-money">{{ number_format((float) $supplier->balance_owed, 0) }}</p>
            @can_do('pos.suppliers.manage')
                <button type="button" class="sws-btn sws-btn--primary sws-btn--block" @click="payModal=true"><i class="fa-solid fa-money-bill-wave"></i> Record payment</button>
            @endcan_do
            <h4>Ledger</h4>
            @forelse($ledger as $entry)
                @php $isPayment = in_array(strtolower((string) $entry->type), $paymentTypes, true); @endphp
                <div class="sws-entry">
                    <i class="fa-solid {{ $isPayment ? 'fa-arrow-down is-in' : 'fa-arrow-up is-out' }}" aria-hidden="true"></i>
                    <span>{{ $isPayment ? 'Payment' : 'Charge' }}<small>{{ \Illuminate\Support\Carbon::parse($entry->created_at)->format('d/m/Y') }}</small></span>
                    <b class="{{ $isPayment ? 'is-in' : 'is-out' }}">{{ $isPayment ? '-' : '+' }}{{ number_format((float) $entry->amount, 0) }}</b>
                </div>
            @empty
                <p class="sws-empty">No activity yet.</p>
            @endforelse
        </section>
    </div>

    {{-- --------------- --}}
    {{-- Edit supplier dialog --}}
    {{-- --------------- --}}
    @include('manage.layouts.partials.supplier-modal', ['open' => 'editModal', 'title' => 'Edit supplier', 'url' => route('manage.pos.suppliers.suppliers.update', $supplier->id), 'method' => 'PUT', 'supplier' => $supplier])

    {{-- --------------- --}}
    {{-- Record payment dialog --}}
    {{-- --------------- --}}
    <div class="sw-md-overlay" x-cloak x-show="payModal" x-transition.opacity @click.self="payModal=false">
        <form class="sw-md-dialog sw-sup" method="POST" action="{{ route('manage.suppliers.payment', $supplier->id) }}" @click.stop>
            @csrf
            <input type="hidden" name="_modal" value="payment">
            <h3>Record payment</h3>
            <div class="sw-sup-field">
                <input type="number" name="amount" required min="0.01" step="any" inputmode="decimal" placeholder=" " value="{{ old('_modal') === 'payment' ? old('amount') : '' }}" x-ref="payAmount" x-effect="if (payModal) $nextTick(() => $refs.payAmount.focus())">
                <label>Amount paid</label>
            </div>
            <p class="sw-md-note">This lowers the balance owed to {{ $name }}. Current balance: {{ number_format((float) $supplier->balance_owed, 0) }}.</p>
            <div class="sw-sup-field">
                <input type="text" name="note" maxlength="255" placeholder=" " value="{{ old('_modal') === 'payment' ? old('note') : '' }}">
                <label>Note (optional)</label>
            </div>
            @if($errors->any() && old('_modal') === 'payment')<p class="sw-md-error">{{ $errors->first() }}</p>@endif
            <div class="sw-md-actions">
                <button type="button" class="sw-md-cancel" @click="payModal=false">Cancel</button>
                <button type="submit" class="sw-md-save">Record</button>
            </div>
        </form>
    </div>
</div>
@endsection