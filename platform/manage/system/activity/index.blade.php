{{-- ------------------- Audit trail ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Audit trail')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Audit trail',
        'lead'   => 'Every change made in the admin panel, who made it and when.',
        'crumbs' => ['Audit Trails' => null],
    ])

    <form method="GET" class="border nm-surface rounded-card p-4 mb-6 flex flex-wrap items-end gap-3" data-no-loader>
        <div class="min-w-[180px]">
            <label class="nm-label">Module</label>
            <select name="module" class="nm-select">
                <option value="">All modules</option>
                @foreach ($modules as $module)
                    <option value="{{ $module }}" @selected(request('module') === $module)>{{ Str::headline($module) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="nm-label">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="nm-input">
        </div>
        <button type="submit" class="nm-btn nm-btn--ink nm-btn--sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('manage.activity-log.index') }}" class="nm-btn nm-btn--ghost nm-btn--sm">Reset</a>
    </form>

    @if ($entries->isEmpty())
        @include('manage.partials.empty', [
            'icon' => 'fa-clock-rotate-left', 'title' => 'No activity matches that',
        ])
    @else

        <div class="nm-panel">
            <div class="overflow-x-auto">
                <table class="nm-table">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>Who</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr>
                                <td class="text-xs text-ink-muted whitespace-nowrap">{{ $entry->created_at?->format('d M Y, H:i') }}</td>
                                <td class="text-xs text-ink-muted">
                                    {{ $entry->user ? trim($entry->user->first_name . ' ' . $entry->user->last_name) : 'System' }}
                                </td>
                                <td class="text-xs text-ink-muted">{{ Str::headline($entry->module ?: '—') }}</td>
                                <td>
                                    <span class="nm-badge nm-badge--outline">{{ Str::headline($entry->action) }}</span>
                                    @if ($entry->reference_type && $entry->reference_id)
                                        <span class="block text-[11px] text-ink-muted mt-1">
                                            {{ class_basename($entry->reference_type) }} #{{ $entry->reference_id }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-xs text-ink-muted max-w-sm">{{ $entry->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $entries->links() }}</div>
    @endif

@endsection
