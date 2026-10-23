{{-- ------------------- Staff accounts ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Staff')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Staff',
        'lead'   => 'Administrators and staff who can sign in to this admin panel.',
        'crumbs' => ['Staff & Agents' => null],
        'actions' => '<a href="' . route('manage.system-users.create') . '" class="nm-btn nm-btn--primary nm-btn--sm"><i class="fas fa-plus"></i> Add staff</a>',
    ])

    @include('manage.partials.filters', ['search' => 'Name or email'])

    @if ($users->isEmpty())
        @include('manage.partials.empty', [
            'icon' => 'fa-user-shield', 'title' => 'No staff match that',
            'message' => 'Add an account for anyone who needs to sign in here.',
            'action' => '<a href="' . route('manage.system-users.create') . '" class="nm-btn nm-btn--primary">Add staff</a>',
        ])
    @else

        <div class="nm-panel">
            <div class="overflow-x-auto">
                <table class="nm-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="nm-table__num">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr onclick="window.location='{{ route('manage.system-users.show', $user->id) }}'" class="cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-8 h-8 rounded-pill bg-secondary-light text-secondary text-xs font-bold flex items-center justify-center shrink-0">
                                            {{ $user->initials }}
                                        </span>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-ink">{{ $user->name }}</span>
                                            <span class="block text-xs text-ink-muted">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs text-ink-muted">{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                                <td>@include('manage.partials.status', ['value' => $user->status])</td>
                                <td class="nm-table__num" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end gap-1">
                                        <form method="POST" action="{{ route('manage.system-users.toggle', $user->id) }}" data-no-loader>
                                            @csrf
                                            <button type="submit" title="Suspend or reinstate"
                                                    class="w-8 h-8 flex items-center justify-center text-ink-muted hover:text-primary transition">
                                                <i class="fas {{ $user->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('manage.system-users.edit', $user->id) }}" title="Edit"
                                           class="w-8 h-8 flex items-center justify-center text-ink-muted hover:text-primary transition">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        @include('manage.partials.delete', [
                                            'action' => route('manage.system-users.destroy', $user->id),
                                            'confirm' => 'Remove ' . $user->name . '?',
                                        ])
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    @endif

@endsection
