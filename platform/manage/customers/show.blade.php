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
<div class="swc">
    <section class="swc-card">
        <h2>{{ trim($customer->first_name.' '.($customer->last_name ?? '')) }}</h2>
        <p class="swc-phone">{{ $customer->phone }}</p>
        <p class="swc-balance">Balance owed: {{ number_format((float) $customer->credit_balance, 0) }}</p>
        <div class="swc-actions">
            <button type="button" class="swc-btn swc-btn--primary" disabled title="Not connected yet"><i class="fa-solid fa-money-bill-wave"></i> Record payment</button>
            <button type="button" class="swc-btn swc-btn--outline" disabled title="Not connected yet"><i class="fa-solid fa-plus"></i> Add debt</button>
            <button type="button" class="swc-btn {{ $owes ? 'swc-btn--outline' : 'swc-btn--ghost' }}" disabled title="Not connected yet"><i class="fa-solid fa-check-double"></i> Mark as paid</button>
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
</style>
@endsection