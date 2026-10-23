{{-- -------------------
     Filter bar. A search box, any number of dropdowns, an optional date range
     and a clear link that returns to the unfiltered list.
------------------- --}}
<form method="GET" class="border nm-surface rounded-card p-4 mb-6 flex flex-wrap items-end gap-3" data-no-loader>

    @isset($search)
        <div class="flex-1 min-w-[200px]">
            <label class="nm-label">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $search }}" class="nm-input">
        </div>
    @endisset

    @foreach (($selects ?? []) as $field => $config)
        <div class="min-w-[150px]">
            <label class="nm-label">{{ $config['label'] }}</label>
            <select name="{{ $field }}" class="nm-select">
                <option value="">{{ $config['blank'] ?? 'All' }}</option>
                @foreach ($config['options'] as $key => $text)
                    <option value="{{ $key }}" @selected((string) request($field) === (string) $key)>{{ $text }}</option>
                @endforeach
            </select>
        </div>
    @endforeach

    @if (! empty($dates))
        <div>
            <label class="nm-label">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="nm-input">
        </div>
        <div>
            <label class="nm-label">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="nm-input">
        </div>
    @endif

    <button type="submit" class="nm-btn nm-btn--ink nm-btn--sm">
        <i class="fas fa-filter"></i> Filter
    </button>

    @if (request()->hasAny(['q', 'from', 'to', 'status', 'category', 'type', 'filter', 'placement', 'group', 'source', 'payment_status', 'branch', 'unit', 'device', 'module', 'user', 'agent_id']))
        <a href="{{ url()->current() }}" class="nm-btn nm-btn--ghost nm-btn--sm">Clear</a>
    @endif

</form>
