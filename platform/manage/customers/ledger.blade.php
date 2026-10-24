@extends('manage.layouts.workspace')
@section('title', $item[1] ?? 'Credit Ledger')
@section('heading', $item[1] ?? 'Credit Ledger')
@section('content')
<form method="GET" action="{{ route('manage.customers.ledger') }}" class="swl">
    <p class="swl-summary">{{ $owingCount }} customers owing &bull; Total {{ number_format($owingTotal, 0) }}</p>

    {{-- --------------- --}}
    {{-- Search, press Enter to apply --}}
    {{-- --------------- --}}
    <div class="sw-search swl-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="{{ $search }}" placeholder="Search name, phone or email" aria-label="Search name, phone or email"></div>

    {{-- --------------- --}}
    {{-- Filters apply as soon as they change --}}
    {{-- --------------- --}}
    <div class="swl-filters">
        <select name="balance" onchange="this.form.submit()" aria-label="Balance filter">
            <option value="all" @selected($balance === 'all')>All balances</option>
            <option value="over_limit" @selected($balance === 'over_limit')>Over credit limit</option>
        </select>
        <select name="sort" onchange="this.form.submit()" aria-label="Sort order">
            <option value="highest" @selected($sort === 'highest')>Highest balance first</option>
            <option value="lowest" @selected($sort === 'lowest')>Lowest balance first</option>
            <option value="name" @selected($sort === 'name')>Name A to Z</option>
        </select>
    </div>

    <p class="swl-count">{{ $records->total() }} matching customers</p>
</form>

@if($records->count())
    <section class="sw-people-list swl-card" aria-label="Customers owing">
        @foreach($records as $person)
            <a class="sw-person sw-person-row swl-row" href="{{ route('manage.customers.show', $person->id) }}">
                <span class="swl-avatar"><i class="fa-regular fa-user" aria-hidden="true"></i></span>
                <div><strong>{{ trim($person->first_name.' '.($person->last_name ?? '')) }}</strong><small>{{ $person->phone ?? '' }}</small></div>
                <span class="swl-balance">{{ number_format((float) $person->credit_balance, 0) }}</span>
            </a>
        @endforeach
    </section>
    {{ $records->links() }}
@else
    <div class="sw-empty swl-empty">No customers match these filters.</div>
@endif

<style>
/* --------------- */
/* Credit ledger */
/* --------------- */
.swl-summary{margin:4px 0 22px;font-size:16px;color:#2d384c}
.swl .swl-search{margin:0 0 28px;background:transparent;padding-left:8px}
.swl-filters{display:flex;flex-wrap:wrap;gap:24px}
.swl-filters select{min-width:200px;height:40px;padding:0 6px 0 0;border:0;border-bottom:1px solid #b9b2c7;border-radius:0;background:transparent;font:inherit;font-size:18px;color:#1d1b20;cursor:pointer;outline:0}
.swl-filters select:focus{border-bottom-color:#713fce}
.swl-count{margin:14px 0 18px;font-size:16px;color:#697c9a}
.swl-card{background:#fff;border-radius:24px;padding:12px 16px}
.swl-row{border-bottom:0;padding:18px 8px;text-decoration:none}
.swl-avatar{display:grid;place-items:center;flex:none;width:52px;height:52px;border-radius:50%;background:#f1ebfa;color:#713fce;font-size:20px}
.swl-balance{font-size:15px;color:#2d384c}
.swl-empty{margin:18vh 0;font-size:18px;color:#2d384c}
@media(max-width:850px){.swl-filters{gap:16px}.swl-filters select{min-width:0;flex:1;font-size:16px}}
</style>
@endsection