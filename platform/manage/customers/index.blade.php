<form class="sw-search-card" action="{{ route('manage.workspace.customers',['page'=>'index']) }}" method="GET">
    <div class="sw-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input name="q" value="{{ $search ?? '' }}" placeholder="Search customers" aria-label="Search customers">
    </div>
</form>
<section class="sw-people-list" aria-label="Customers">
@forelse($records as $person)<a class="sw-person sw-person-row" href="{{ route('manage.customers.show',$person->id) }}"><i class="fa-regular fa-user" aria-hidden="true"></i><div><strong>{{ trim($person->first_name.' '.($person->last_name??'')) }}</strong><small>{{ $person->phone ?? '' }}</small></div><span class="sw-person-status">{{ (float)$person->credit_balance>0 ? 'Owing '.number_format((float)$person->credit_balance,0) : 'Paid up' }}</span></a>@empty
    <div class="sw-empty">No matching customers found.</div>
    @endforelse
</section>
    {{-- <div class="sw-page-tools"><span>{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</span><nav class="sw-pager" aria-label="Customer pages"><a @if($records->previousPageUrl()) href="{{ $records->previousPageUrl() }}" @else aria-disabled="true" @endif>Previous</a><a @if($records->nextPageUrl()) href="{{ $records->nextPageUrl() }}" @else aria-disabled="true" @endif>Next</a></nav></div> --}}
