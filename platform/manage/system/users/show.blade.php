{{-- ------------------- One staff account ------------------- --}}
@extends('manage.layouts.app')

@section('title', $user->name)

@section('content')

    @include('manage.partials.head', [
        'title'  => $user->name,
        'lead'   => $user->email,
        'crumbs' => ['Staff & Agents' => route('manage.system-users.index'), $user->name => null],
        'actions' => '<a href="' . route('manage.system-users.edit', $user->id) . '" class="nm-btn nm-btn--primary nm-btn--sm"><i class="fas fa-pen"></i> Edit</a>',
    ])

    @if ($isAgent)

        <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Status</p>
                <p class="mt-1">@include('manage.partials.presence-badge', ['status' => $metrics['current_status'], 'since' => $metrics['status_since']])</p>
                @if ($metrics['status_since'])
                    <p class="text-[11px] mt-1" style="color: {{ \App\Models\CallCenter\AgentStatusLog::presenceTier($metrics['current_status'], $metrics['status_since'])['bg'] }}">Since {{ $metrics['status_since']->diffForHumans() }}</p>
                @endif
            </div>
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Last call handled</p>
                <p class="font-heading font-medium text-lg text-ink mt-1 leading-tight">
                    {{ $metrics['last_call_handled']?->diffForHumans() ?? 'No calls yet' }}
                </p>
            </div>
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Calls handled</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none">{{ $metrics['total_calls'] }}</p>
            </div>
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Avg handle time</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none">
                    {{ $metrics['avg_handle_time'] ? gmdate('i:s', $metrics['avg_handle_time']) : '—' }}
                </p>
            </div>
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Resolution rate</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none">
                    {{ $metrics['resolution_rate'] !== null ? $metrics['resolution_rate'] . '%' : '—' }}
                </p>
            </div>
            <div class="border nm-surface rounded-card p-4">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">CSAT</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none">
                    {{ $metrics['csat_avg'] ? round($metrics['csat_avg'], 1) . '/5' : '—' }}
                </p>
            </div>
        </div>

        <div class="nm-panel mb-6">
            <div class="nm-panel__head">Calls this week</div>
            <div class="nm-panel__body">
                <canvas id="agentWeekChart" height="90"></canvas>
            </div>
        </div>

        <div class="nm-panel mb-6">
            <div class="nm-panel__head">Correct their status</div>
            <div class="nm-panel__body">
                <p class="text-xs text-ink-muted mb-4">
                    Use this if their real status doesn't match what's shown above, for example after closing their browser without signing out.
                </p>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (['online' => 'Mark online', 'break' => 'Mark on break', 'offline' => 'Mark offline'] as $value => $label)
                        <form method="POST" action="{{ route('manage.system-users.presence', $user->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $value }}">
                            <button type="submit" class="nm-btn nm-btn--ghost nm-btn--sm">{{ $label }}</button>
                        </form>
                    @endforeach

                    @if ($metrics['active_call'])
                        <form method="POST" action="{{ route('manage.calls.session.hangup', $metrics['active_call']->id) }}"
                              onsubmit="return confirm('End this call? Use this only if it is stuck and was never actually hung up.')"
                              class="ml-auto">
                            @csrf
                            <button type="submit" class="nm-btn nm-btn--sm border-2 border-primary text-primary hover:bg-primary hover:text-white">
                                <i class="fas fa-phone-slash"></i> Force end current call
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    @endif

    <div class="grid lg:grid-cols-3 gap-6 items-start">

        <div class="lg:col-span-2 space-y-6">

            <div class="nm-panel">
                <div class="nm-panel__head">Roles and what they unlock</div>

                @if ($user->roles->isEmpty())
                    <p class="p-8 text-center text-sm text-ink-muted">No role assigned. This account cannot reach anything yet.</p>
                @else
                    <div class="divide-y divide-line">
                        @foreach ($user->roles as $role)
                            <div class="p-4">
                                <p class="font-semibold text-ink text-sm">{{ $role->name }}</p>
                                @if ($role->description)
                                    <p class="text-xs text-ink-muted mt-0.5">{{ $role->description }}</p>
                                @endif
                                @if ($role->permissions->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        @foreach ($role->permissions as $permission)
                                            <span class="nm-badge nm-badge--outline">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <div class="space-y-6 lg:sticky lg:top-6">

            <div class="nm-panel">
                <div class="nm-panel__head">Account</div>
                <div class="nm-panel__body space-y-2.5 text-sm">
                    <div class="flex justify-between"><span class="text-ink-muted">Status</span>
                        @include('manage.partials.status', ['value' => $user->status])</div>
                    @if ($user->phone)
                        <div class="flex justify-between"><span class="text-ink-muted">Phone</span><span>{{ $user->phone }}</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-ink-muted">Joined</span><span>{{ $user->created_at?->format('d M Y') }}</span></div>
                    @if ($user->last_login_at)
                        <div class="flex justify-between"><span class="text-ink-muted">Last signed in</span><span>{{ $user->last_login_at->diffForHumans() }}</span></div>
                    @endif
                </div>
            </div>

            <div class="nm-panel">
                <div class="nm-panel__body space-y-2.5">
                    <form method="POST" action="{{ route('manage.system-users.toggle', $user->id) }}">
                        @csrf
                        <button type="submit" class="nm-btn nm-btn--ghost nm-btn--block nm-btn--sm">
                            <i class="fas {{ $user->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                            {{ $user->status === 'active' ? 'Suspend this account' : 'Reinstate this account' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('manage.system-users.destroy', $user->id) }}"
                          onsubmit="return confirm('Remove this account permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="nm-btn nm-btn--block nm-btn--sm border-2 border-primary text-primary hover:bg-primary hover:text-white">
                            Remove account
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection

@if ($isAgent)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        if (! window.__agentWeekChartBound) {
            window.__agentWeekChartBound = true;

            document.addEventListener('turbo:load', function () {
                const canvas = document.getElementById('agentWeekChart');
                if (! canvas) return;

                const existing = window.Chart.getChart(canvas);
                if (existing) existing.destroy();

                const primary = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#f7af39';
                const muted = getComputedStyle(document.documentElement).getPropertyValue('--muted-color').trim() || '#7f8399';

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: @json($metrics['week_chart']['labels']),
                        datasets: [{
                            label: 'Calls',
                            data: @json($metrics['week_chart']['counts']),
                            backgroundColor: primary,
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { color: muted }, grid: { display: false } },
                            y: { ticks: { color: muted, precision: 0 }, grid: { color: 'rgba(127,131,153,0.15)' }, beginAtZero: true },
                        },
                    },
                });
            });
        }
    </script>
    @endpush
@endif
