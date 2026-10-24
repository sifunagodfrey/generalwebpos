@extends('manage.layouts.workspace')
@section('title','Customer tab')
@section('heading','Customer tab')
@section('content')
<div class="swc">
    <section class="swc-card">
        <h2>{{ trim($customer->first_name.' '.($customer->last_name ?? '')) }}</h2>
        <p class="swc-phone">{{ $customer->phone }}</p>
        <p class="swc-balance">Balance owed: {{ number_format((float) $customer->credit_balance, 0) }}</p>
        <div class="swc-actions">
            <button type="button" class="swc-btn swc-btn--primary" disabled title="Not connected yet"><i class="fa-solid fa-money-bill-wave"></i> Record payment</button>
            <button type="button" class="swc-btn swc-btn--outline" disabled title="Not connected yet"><i class="fa-solid fa-plus"></i> Add debt</button>
            <button type="button" class="swc-btn swc-btn--ghost" disabled><i class="fa-solid fa-check-double"></i> Mark as paid</button>
        </div>
    </section>

    <h3>Recent sales</h3>
    @forelse($sales as $sale)
        <a class="swc-row" href="{{ route('manage.sales.receipt', $sale->id) }}">
            <span><b>Receipt #{{ $sale->id }}</b><small>{{ $sale->completed_at }}</small></span>
            <strong>{{ number_format((float) $sale->total, 0) }}</strong>
        </a>
    @empty
        <p class="swc-empty swc-empty--left">No sales yet.</p>
    @endforelse

    <h3>Ledger</h3>
    @forelse($ledger as $entry)
        <div class="swc-row">
            <span><b>{{ ucfirst($entry->type) }}</b><small>{{ $entry->note }} {{ $entry->created_at }}</small></span>
            <strong>{{ number_format((float) $entry->amount, 0) }}</strong>
        </div>
    @empty
        <p class="swc-empty">No activity yet.</p>
    @endforelse
</div>

<style>
/* --------------- */
/* Customer tab */
/* --------------- */
.swc h3{margin:34px 0 14px;font-size:16px;font-weight:700;color:#2d384c}
.swc-card{background:#fff;border:1px solid #e4dafa;border-radius:12px;padding:22px 24px}
.swc-card h2{margin:0 0 4px;font-size:22px;font-weight:700;color:#2d384c}
.swc-phone{margin:0 0 22px;font-size:16px;color:#2d384c}
.swc-balance{margin:0 0 14px;font-size:16px;font-weight:700;color:#2d384c}
.swc-actions{display:flex;flex-wrap:wrap;gap:12px}
.swc-btn{display:inline-flex;align-items:center;gap:12px;height:36px;padding:0 28px;border-radius:8px;font:inherit;font-size:16px;font-weight:500}
.swc-btn--primary{border:0;background:#713fce;color:#fff}
.swc-btn--outline{border:1px solid #79747e;background:#fff;color:#713fce}
.swc-btn--ghost{border:1px solid #e4dafa;background:#fff;color:#9aa3b5}
.swc-btn:disabled{cursor:not-allowed}
.swc-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 4px;border-bottom:1px solid #d9cfe9;color:#2d384c}
.swc-row span{display:grid;gap:4px}
.swc-row small{font-size:13px;color:#697c9a}
.swc-empty{margin:50px 0;text-align:center;font-size:18px;color:#697c9a}
.swc-empty--left{text-align:left;margin:40px 0}
</style>
@endsection