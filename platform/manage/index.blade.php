{{-- -------------------
     Dashboard.

     An at a glance summary, not the full wallboard, that lives at
     Reports & Analytics > Wallboard. The four top tiles read from the
     liveStats store so they update on their own every 20 seconds, no
     reload needed, the same store that feeds the sidebar badges.
------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Dashboard')

@push('topbar-actions')
    <div x-data="{ open: false, number: '' }">
        <button type="button" @click="open = true"
                class="nm-btn nm-btn--primary nm-btn--sm">
            <i class="fas fa-phone"></i> Dial
        </button>

        <div x-show="open" x-cloak class="nm-modal-overlay fixed inset-0 z-[9996] flex items-center justify-center px-4 bg-overlay/60"
             @keydown.escape.window="open = false">
            <div class="bg-surface text-ink rounded-card shadow-lg w-full max-w-sm overflow-hidden" @click.outside="open = false">
                <div class="px-5 py-4 border-b border-line">
                    <p class="font-heading font-bold">Dial a number</p>
                    <p class="text-xs text-ink-muted mt-1">
                        <i class="fas fa-circle-info"></i> Places a real call through your configured voice provider.
                    </p>
                </div>
                <div class="p-5">
                    <label class="nm-label">Phone number</label>
                    <input type="tel" x-model="number" placeholder="+254712345678" class="nm-input" x-ref="dialInput"
                           @keydown.enter="if (number) { $store.callSession.dial(number); open = false; number = ''; }">
                </div>
                <div class="px-5 py-4 border-t border-line flex items-center justify-end gap-2">
                    <button type="button" @click="open = false" class="nm-btn nm-btn--ghost nm-btn--sm">Cancel</button>
                    <button type="button" :disabled="! number"
                            @click="$store.callSession.dial(number); open = false; number = '';"
                            class="nm-btn nm-btn--primary nm-btn--sm">
                        <i class="fas fa-phone"></i> Dial
                    </button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" onclick="Alpine.store('liveStats').refresh()"
            class="w-10 h-10 rounded-pill flex items-center justify-center text-ink-muted hover:text-primary transition" title="Refresh now">
        <i class="fas fa-rotate"></i>
    </button>
@endpush

@section('content')

    @include('manage.partials.head', [
        'title' => 'Good ' . (now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening')) . ', ' . auth()->user()->first_name,
        'lead'  => 'Here is where things stand right now, updating on its own.',
    ])

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" x-data>

        <a href="{{ route('manage.calls.index') }}" class="nm-tile--green block border border-l-4 rounded-card p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Calls today</p>
                    <p class="nm-tile__value font-heading font-medium text-2xl mt-1 leading-none" x-text="$store.liveStats.calls_today">{{ $calls_today }}</p>
                </div>
                <span class="nm-tile__icon w-9 h-9 rounded-pill flex items-center justify-center shrink-0">
                    <i class="fas fa-phone text-sm"></i>
                </span>
            </div>
        </a>

        <div class="nm-tile--red border border-l-4 rounded-card p-4">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Missed today</p>
                    <p class="nm-tile__value font-heading font-medium text-2xl mt-1 leading-none" x-text="$store.liveStats.missed_today">{{ $missed_today }}</p>
                </div>
                <span class="nm-tile__icon w-9 h-9 rounded-pill flex items-center justify-center shrink-0">
                    <i class="fas fa-phone-slash text-sm"></i>
                </span>
            </div>
        </div>

        <a href="{{ route('manage.reports.wallboard') }}" class="nm-tile--yellow block border border-l-4 rounded-card p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Agents online</p>
                    <p class="nm-tile__value font-heading font-medium text-2xl mt-1 leading-none" x-text="$store.liveStats.agents_online">{{ $agents_online }}</p>
                </div>
                <span class="nm-tile__icon w-9 h-9 rounded-pill flex items-center justify-center shrink-0">
                    <i class="fas fa-headset text-sm"></i>
                </span>
            </div>
        </a>

        <a href="{{ route('manage.follow-ups.index') }}" class="nm-tile--orange block border border-l-4 rounded-card p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">Follow ups</p>
                    <p class="nm-tile__value font-heading font-medium text-2xl mt-1 leading-none">{{ $followUpsOverdue + $followUpsDueToday }}</p>
                    <p class="text-xs text-ink-muted mt-1">
                        @if ($followUpsOverdue > 0)
                            <span class="nm-tile__value font-semibold">{{ $followUpsOverdue }} overdue</span> &middot;
                        @endif
                        {{ $followUpsDueToday }} due today
                    </p>
                </div>
                <span class="nm-tile__icon w-9 h-9 rounded-pill flex items-center justify-center shrink-0">
                    <i class="fas fa-calendar-check text-sm"></i>
                </span>
            </div>
        </a>

    </div>

    <div x-data x-show="$store.callSession.queuedCalls.length > 0" x-cloak
         class="nm-panel mb-6 border-l-4" style="border-left-color: #ea580c">
        <div class="nm-panel__head flex items-center justify-between">
            <span><i class="fas fa-phone-volume mr-1" style="color: #ea580c"></i> Calls waiting</span>
            <span class="nm-badge" style="background-color: #ea580c; color: #ffffff" x-text="$store.callSession.queuedCalls.length"></span>
        </div>
        <div class="divide-y divide-line">
            <template x-for="call in $store.callSession.queuedCalls" :key="call.id">
                <div class="flex items-center justify-between gap-3 px-5 py-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink truncate" x-text="call.contact_name || call.caller_number"></p>
                        <p class="text-xs text-ink-muted truncate">
                            <span x-text="call.queue_name || 'No queue'"></span>
                            <template x-if="call.ivr_path">
                                <span> &middot; <i class="fas fa-route text-[10px]"></i> <span x-text="call.ivr_path"></span></span>
                            </template>
                        </p>
                    </div>
                    <button type="button" @click="$store.callSession.pickUp(call.id)" class="nm-btn nm-btn--primary nm-btn--sm shrink-0">
                        <i class="fas fa-phone"></i> Pick up
                    </button>
                </div>
            </template>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">

        <div class="nm-panel lg:col-span-2">
            <div class="nm-panel__head">Calls this week</div>
            <div class="nm-panel__body">
                <canvas id="weekChart" height="110"></canvas>
            </div>
        </div>

        <div class="nm-panel lg:col-span-1">
            <div class="nm-panel__head flex items-center justify-between">
                <span>Agents</span>
                <a href="{{ route('manage.reports.wallboard') }}" class="text-xs text-primary hover:underline">Wallboard</a>
            </div>
            <div class="nm-panel__body space-y-1 max-h-[280px] overflow-y-auto">
                @forelse ($agents as $agent)
                    @php
                        $tier = \App\Models\CallCenter\AgentStatusLog::presenceTier($agent->current_status, $agent->status_since);
                        $label = match ($agent->current_status) {
                            'online' => 'Online now',
                            'on_call' => 'On a call',
                            'break' => 'On break',
                            default => $agent->status_since ? 'Last seen ' . $agent->status_since->diffForHumans() : 'Never online',
                        };
                    @endphp
                    <a href="{{ route('manage.system-users.show', $agent->id) }}" class="flex items-center gap-3 py-2 px-1 rounded-card hover:bg-steel transition">
                        <span class="relative shrink-0">
                            <span class="w-8 h-8 rounded-pill bg-secondary-light text-secondary text-[11px] font-bold flex items-center justify-center">
                                {{ $agent->initials }}
                            </span>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-pill border-2 border-surface" style="background-color: {{ $tier['bg'] }}"></span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-ink truncate">{{ $agent->name }}</p>
                            <p class="text-xs truncate" style="color: {{ $tier['bg'] }}">{{ $label }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-ink-muted py-6 text-center">No agents yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <div class="nm-panel lg:col-span-2">
            <div class="nm-panel__head">Recent calls</div>
            <div class="overflow-x-auto">
                @if ($recentCalls->isEmpty())
                    <div class="p-6">
                        @include('manage.partials.empty', [
                            'icon' => 'fa-phone', 'title' => 'No calls yet',
                            'message' => 'This fills in once the voice provider is connected.',
                        ])
                    </div>
                @else
                    <table class="nm-table">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>Caller</th>
                                <th>Agent</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentCalls as $call)
                                <tr onclick="window.location='{{ route('manage.calls.show', $call->id) }}'" class="cursor-pointer">
                                    <td class="text-ink">
                                        {{ $call->started_at?->diffForHumans() ?? '—' }}
                                    </td>
                                    <td class="text-xs text-ink-muted">{{ $call->contact->name ?? $call->caller_number }}</td>
                                    <td class="text-xs text-ink-muted">{{ $call->agent->name ?? '—' }}</td>
                                    <td>@include('manage.partials.status', ['value' => $call->status])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="nm-panel lg:col-span-1 h-fit">
            <div class="nm-panel__head">Set up</div>
            <div class="nm-panel__body space-y-3 text-sm">
                <a href="{{ route('manage.queues.index') }}" class="flex items-center justify-between group/link">
                    <span class="text-ink group-hover/link:text-primary transition">Queues</span>
                    <span class="text-ink-muted">{{ $queue_count }}</span>
                </a>
                <a href="{{ route('manage.ivr.index') }}" class="flex items-center justify-between group/link">
                    <span class="text-ink group-hover/link:text-primary transition">IVR menus</span>
                    <span class="text-ink-muted">{{ $ivrMenuCount }}</span>
                </a>
                <a href="{{ route('manage.settings.voice-provider.index') }}" class="flex items-center justify-between group/link">
                    <span class="text-ink group-hover/link:text-primary transition">Voice provider</span>
                    <i class="fas fa-arrow-right text-xs text-ink-muted"></i>
                </a>
                <a href="{{ route('manage.settings.business-hours.index') }}" class="flex items-center justify-between group/link">
                    <span class="text-ink group-hover/link:text-primary transition">Business hours</span>
                    <i class="fas fa-arrow-right text-xs text-ink-muted"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- Team --}}
    <div class="nm-panel mt-6">
        <div class="nm-panel__head flex items-center justify-between">
            <span>Team</span>
            <a href="{{ route('manage.reports.wallboard') }}" class="text-xs text-primary hover:underline">Full wallboard</a>
        </div>
        <div class="overflow-x-auto">
            @if ($agents->isEmpty())
                <div class="p-6">
                    @include('manage.partials.empty', [
                        'icon' => 'fa-headset', 'title' => 'No agents yet',
                        'message' => 'Assign the agent or supervisor role to a staff account to see them here.',
                    ])
                </div>
            @else
                <table class="nm-table">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Last seen</th>
                            <th>Last call handled</th>
                            <th class="nm-table__num">Call controls</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr>
                                <td class="font-semibold text-ink">{{ $agent->name }}</td>
                                <td>@include('manage.partials.presence-badge', ['status' => $agent->current_status, 'since' => $agent->status_since])</td>
                                <td class="text-xs" style="color: {{ \App\Models\CallCenter\AgentStatusLog::presenceTier($agent->current_status, $agent->status_since)['bg'] }}">{{ $agent->status_since?->diffForHumans() ?? '—' }}</td>
                                <td class="text-xs text-ink-muted">{{ $agent->last_response?->diffForHumans() ?? 'No calls yet' }}</td>
                                <td class="nm-table__num">
                                    <span class="text-xs text-ink-muted/70 italic" title="Available once a call is in progress and the voice provider is connected">
                                        Hold / Escalate — on an active call
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('turbo:load', function () {

        const ink = getComputedStyle(document.documentElement).getPropertyValue('--ink-color').trim() || '#161d45';
        const primary = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#f7af39';
        const muted = getComputedStyle(document.documentElement).getPropertyValue('--muted-color').trim() || '#7f8399';

        new Chart(document.getElementById('weekChart'), {
            type: 'line',
            data: {
                labels: @json($weekChart['labels']),
                datasets: [
                    {
                        label: 'Answered',
                        data: @json($weekChart['answered']),
                        borderColor: primary,
                        backgroundColor: primary,
                        tension: 0.3,
                    },
                    {
                        label: 'Missed',
                        data: @json($weekChart['missed']),
                        borderColor: ink,
                        backgroundColor: ink,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { color: muted, boxWidth: 10 } } },
                scales: {
                    x: { ticks: { color: muted }, grid: { display: false } },
                    y: { ticks: { color: muted, precision: 0 }, grid: { color: 'rgba(127,131,153,0.15)' }, beginAtZero: true },
                },
            },
        });

    });
</script>
@endpush