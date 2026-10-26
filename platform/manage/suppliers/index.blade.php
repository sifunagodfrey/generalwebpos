<form class="sw-search-card" action="{{ route('manage.workspace.suppliers',['page'=>'index']) }}" method="GET">
    <div class="sw-search"><i class="fa-solid fa-magnifying-glass"></i><input name="q" value="{{ $search ?? '' }}" placeholder="Search suppliers" aria-label="Search suppliers"></div>
</form>
<section class="sw-people-list" aria-label="Suppliers">
@forelse($records as $person)<a class="sw-person sw-person-row sw-general" href="{{ route('manage.suppliers.show', $person->id) }}"><i class="fa-solid fa-truck" aria-hidden="true"></i><div><strong>{{ trim(($person->first_name ?? '').' '.($person->last_name ?? '')) ?: ($person->name ?? 'Supplier') }}</strong><small>{{ $person->phone ?? '' }}</small></div><i class="fa-solid fa-chevron-right sw-chevron" aria-hidden="true"></i></a>@empty
    <div class="sw-empty">No matching suppliers found.</div>
@endforelse
</section>
<div class="sw-page-tools"><span>{{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</span><nav class="sw-pager" aria-label="Supplier pages"><a @if($records->previousPageUrl()) href="{{ $records->previousPageUrl() }}" @else aria-disabled="true" @endif>Previous</a><a @if($records->nextPageUrl()) href="{{ $records->nextPageUrl() }}" @else aria-disabled="true" @endif>Next</a></nav></div>