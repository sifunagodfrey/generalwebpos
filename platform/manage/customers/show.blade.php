@extends('manage.layouts.workspace')
@section('title','Customer tab')
@section('heading','Customer tab')
@section('content')
@php
    // ---------------
    // Ledger types that reduce the balance, everything else counts as a charge
    // ---------------
    $paymentTypes = ['payment', 'repayment'];
    $owes = (float) $customer->credit_balance > 0;
@endphp
<div class="swc" x-data="{ modal: @js(old('_modal')) }">
        <section class="swc-card">
        @if(session('status'))<p class="swc-flash">{{ session('status') }}</p>@endif
        @if($errors->any() && !old('_modal'))<p class="swc-flash swc-flash--error">{{ $errors->first() }}</p>@endif
        <h2>{{ trim($customer->first_name.' '.($customer->last_name ?? '')) }}</h2>
        <p class="swc-phone">{{ $customer->phone }}</p>
        <p class="swc-balance">Balance owed: {{ number_format((float) $customer->credit_balance, 0) }}</p>
        <div class="swc-actions">
            @can_do('pos.customers.manage')
                <button type="button" class="swc-btn swc-btn--primary" :disabled="{{ $owes ? 'false' : 'true' }}" @click="modal='payment'"><i class="fa-solid fa-money-bill-wave"></i> Record payment</button>
                <button type="button" class="swc-btn swc-btn--outline" @click="modal='debt'"><i class="fa-solid fa-plus"></i> Add debt</button>
                <button type="button" class="swc-btn {{ $owes ? 'swc-btn--outline' : 'swc-btn--ghost' }}" @if(!$owes) disabled @endif @click="modal='settle'"><i class="fa-solid fa-check-double"></i> Mark as paid</button>
            @endcan_do
        </div>
    </section>

    <h3>Recent sales</h3>
    @if($sales->count())
        <div class="swc-sales">
            @foreach($sales as $sale)
                <a class="swc-sale" href="{{ route('manage.sales.receipt', $sale->id) }}">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                    <small>{{ \Illuminate\Support\Carbon::parse($sale->completed_at)->format('d/m/Y') }}</small>
                    <strong>{{ number_format((float) $sale->total, 0) }}</strong>
                </a>
            @endforeach
        </div>
    @else
        <p class="swc-empty swc-empty--left">No sales yet.</p>
    @endif

    <h3>Ledger</h3>
    @forelse($ledger as $entry)
        @php $isPayment = in_array(strtolower((string) $entry->type), $paymentTypes, true); @endphp
        <div class="swc-entry">
            <i class="fa-solid {{ $isPayment ? 'fa-arrow-down is-in' : 'fa-arrow-up is-out' }}" aria-hidden="true"></i>
            <span>{{ $isPayment ? 'Payment' : 'Charge' }}</span>
            <b class="{{ $isPayment ? 'is-in' : 'is-out' }}">{{ $isPayment ? '-' : '+' }}{{ number_format((float) $entry->amount, 0) }}</b>
        </div>
    @empty
        <p class="swc-empty">No activity yet.</p>
    @endforelse

    {{-- --------------- --}}
    {{-- Record payment or debt dialog --}}
    {{-- --------------- --}}
    <div class="sw-md-overlay" x-cloak x-show="modal==='payment'" x-transition.opacity @keydown.escape.window="modal=null" @click.self="modal=null">
        <form class="sw-md-dialog sw-cd" method="POST" action="{{ route('manage.customers.payment', $customer->id) }}" @click.stop>
            @csrf
            <input type="hidden" name="_modal" value="payment">
            <h3>Record payment or debt</h3>
            <div class="sw-cd-field">
                <label for="cd-pay-amount">Amount</label>
                <input id="cd-pay-amount" type="number" name="amount" required step="any" value="{{ old('_modal') === 'payment' ? old('amount') : '' }}" x-ref="payAmount" x-effect="if (modal==='payment') $nextTick(() => $refs.payAmount.focus())">
                <p>Positive: payment. Negative: add debt (e.g. -20).</p>
            </div>
            <input class="sw-cd-note" type="text" name="note" maxlength="255" placeholder="Note (optional)" value="{{ old('_modal') === 'payment' ? old('note') : '' }}">
            @if($errors->any() && old('_modal') === 'payment')<p class="sw-md-error">{{ $errors->first() }}</p>@endif
            <div class="sw-md-actions">
                <button type="button" class="sw-md-cancel" @click="modal=null">Cancel</button>
                <button type="submit" class="sw-md-save">Record</button>
            </div>
        </form>
    </div>

    {{-- --------------- --}}
    {{-- Add debt dialog --}}
    {{-- --------------- --}}
    <div class="sw-md-overlay" x-cloak x-show="modal==='debt'" x-transition.opacity @keydown.escape.window="modal=null" @click.self="modal=null">
        <form class="sw-md-dialog sw-cd" method="POST" action="{{ route('manage.customers.debt', $customer->id) }}" @click.stop>
            @csrf
            <input type="hidden" name="_modal" value="debt">
            <h3>Add debt</h3>
            <div class="sw-cd-field">
                <label for="cd-debt-amount">Debt amount</label>
                <input id="cd-debt-amount" type="number" name="amount" required min="0.01" step="any" inputmode="decimal" value="{{ old('_modal') === 'debt' ? old('amount') : '' }}" x-ref="debtAmount" x-effect="if (modal==='debt') $nextTick(() => $refs.debtAmount.focus())">
                <p>This amount will increase the balance owed.</p>
            </div>
            <input class="sw-cd-note" type="text" name="note" maxlength="255" placeholder="Note (optional)" value="{{ old('_modal') === 'debt' ? old('note') : '' }}">
            @if($errors->any() && old('_modal') === 'debt')<p class="sw-md-error">{{ $errors->first() }}</p>@endif
            <div class="sw-md-actions">
                <button type="button" class="sw-md-cancel" @click="modal=null">Cancel</button>
                <button type="submit" class="sw-md-save">Add debt</button>
            </div>
        </form>
    </div>

    {{-- --------------- --}}
    {{-- Mark as paid confirmation --}}
    {{-- --------------- --}}
    <div class="sw-md-overlay" x-cloak x-show="modal==='settle'" x-transition.opacity @keydown.escape.window="modal=null" @click.self="modal=null">
        <form class="sw-md-dialog" method="POST" action="{{ route('manage.customers.settle', $customer->id) }}" @click.stop>
            @csrf
            <input type="hidden" name="_modal" value="settle">
            <h3>Mark as paid</h3>
            <p class="sw-md-note">This records a payment of {{ number_format((float) $customer->credit_balance, 0) }} and clears the balance for {{ trim($customer->first_name.' '.($customer->last_name ?? '')) }}.</p>
            @if($errors->any() && old('_modal') === 'settle')<p class="sw-md-error">{{ $errors->first() }}</p>@endif
            <div class="sw-md-actions">
                <button type="button" class="sw-md-cancel" @click="modal=null">Cancel</button>
                <button type="submit" class="sw-md-save">Confirm</button>
            </div>
        </form>
    </div>
</div>

<style>
/* --------------- */
/* Customer tab */
/* --------------- */
.swc h3{margin:28px 0 12px;font-size:16px;font-weight:700;color:#2d384c}
.swc-card{background:#fff;border:1px solid #e4dafa;border-radius:12px;padding:22px 24px}
.swc-card h2{margin:0 0 4px;font-size:16px;font-weight:700;color:#2d384c}
.swc-phone{margin:0 0 22px;font-size:16px;color:#2d384c}
.swc-balance{margin:0 0 14px;font-size:14px;font-weight:700;color:#2d384c}
.swc-actions{display:flex;flex-wrap:wrap;gap:12px}
.swc-btn{display:inline-flex;align-items:center;gap:12px;height:48px;padding:0 28px;border-radius:16px;font:inherit;font-size:14px;font-weight:500}
.swc-btn--primary{border:0;background:#713fce;color:#fff}
.swc-btn--outline{border:1px solid #79747e;background:#fff;color:#713fce}
.swc-btn--ghost{border:1px solid #e4dafa;background:#fff;color:#9aa3b5}
.swc-btn:disabled{cursor:not-allowed}

/* --------------- */
/* Recent sales cards */
/* --------------- */
.swc-sales{display:flex;flex-wrap:wrap;gap:16px}
.swc-sale{display:grid;gap:10px;width:220px;padding:16px 18px 18px;border:1px solid #e4dafa;border-radius:12px;background:#fff;color:#2d384c;text-decoration:none}
.swc-sale i{font-size:22px;color:#713fce}
.swc-sale small{font-size:14px;color:#697c9a}
.swc-sale strong{font-size:20px;font-weight:400}
@media(min-width:851px){.swc-sale strong{font-weight:700}}

/* --------------- */
/* Ledger entries */
/* --------------- */
.swc-entry{display:flex;align-items:center;gap:20px;padding:12px 8px;color:#2d384c}
.swc-entry i{width:24px;text-align:center;font-size:22px}
.swc-entry span{flex:1;font-size:16px}
.swc-entry b{font-size:14px;font-weight:400}
.swc-entry .is-out{color:#e34b4b}
.swc-entry .is-in{color:#0a9a62}
.swc-empty{margin:50px 0;text-align:center;font-size:18px;color:#697c9a}
.swc-empty--left{text-align:left;margin:40px 0}
/* --------------- */
/* Mobile: one horizontally scrollable row of sales */
/* --------------- */
@media(max-width:850px){
  .swc-sales{flex-wrap:nowrap;overflow-x:auto;-webkit-overflow-scrolling:touch;scroll-snap-type:x proximity;padding-bottom:6px;scrollbar-width:none}
  .swc-sales::-webkit-scrollbar{display:none}
  .swc-sale{flex:none;width:150px;scroll-snap-align:start}
}

.swc-flash{margin:0 0 14px;padding:12px 14px;border-radius:10px;background:#e0f5e9;color:#137c40;font-size:14px}
.swc-flash--error{background:#fff0ed;color:#b23135}
.swc-btn:disabled{opacity:.55}

/* --------------- */
/* Customer credit dialogs (match the Flutter reference) */
/* --------------- */
.sw-md-dialog.sw-cd{width:auto;min-width:min(300px,100%);max-width:min(380px,100%);padding:28px;border-radius:28px;background:#ece6f0}
.sw-cd h3{margin:0 0 22px;font-size:28px;font-weight:400;line-height:1.25;color:#1d1b20}
.sw-cd-field label{display:block;font-size:14px;color:#713fce}
.sw-cd-field input{display:block;width:100%;height:38px;padding:0;border:0;border-bottom:1px solid #79747e;border-radius:0;background:transparent;outline:0;font:inherit;font-size:16px;color:#1d1b20}
.sw-cd-field input:focus{border-bottom:2px solid #713fce}
.sw-cd-field p{margin:8px 0 0;font-size:14px;line-height:1.5;color:#49454f}
.sw-cd-note{display:block;width:100%;height:46px;margin-top:14px;padding:0;border:0;border-bottom:1px solid #79747e;border-radius:0;background:transparent;outline:0;font:inherit;font-size:18px;color:#1d1b20}
.sw-cd-note::placeholder{color:#49454f}
.sw-cd-note:focus{border-bottom:2px solid #713fce}
.sw-md-dialog.sw-cd .sw-md-actions{margin-top:30px}
.sw-md-dialog.sw-cd .sw-md-save{height:48px;padding:0 24px;border-radius:20px;font-size:16px}
.sw-md-dialog.sw-cd .sw-md-cancel{padding:10px 16px;font-size:16px;font-weight:500}
@media(max-width:850px){.sw-md-dialog.sw-cd{max-width:100%;min-width:100%}.sw-md-dialog.sw-cd .sw-md-save{border-radius:16px}}
</style>
@endsection