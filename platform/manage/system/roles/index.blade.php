{{-- ------------------- Roles ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Roles')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Roles',
        'lead'   => 'What each kind of staff account can reach. Fine grained control lives on the Permissions page.',
        'crumbs' => ['Roles & Permission' => route('manage.roles.index'), 'Roles' => null],
        'actions' => '<a href="' . route('manage.roles.create') . '" class="nm-btn nm-btn--primary nm-btn--sm"><i class="fas fa-plus"></i> Add role</a>',
    ])

    <div class="nm-panel">
        <div class="overflow-x-auto">
            <table class="nm-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th class="nm-table__num">Permissions</th>
                        <th class="nm-table__num">Staff</th>
                        <th class="nm-table__num">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr onclick="window.location='{{ route('manage.roles.edit', $role->id) }}'" class="cursor-pointer">
                            <td>
                                <span class="font-semibold text-ink">{{ $role->name }}</span>
                                @if ($role->is_system)
                                    <span class="nm-badge nm-badge--outline ml-1">System</span>
                                @endif
                                @if ($role->description)
                                    <span class="block text-xs text-ink-muted">{{ $role->description }}</span>
                                @endif
                            </td>
                            <td class="nm-table__num text-xs text-ink-muted">{{ $role->permissions_count }}</td>
                            <td class="nm-table__num text-xs text-ink-muted">{{ $role->users_count }}</td>
                            <td class="nm-table__num" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('manage.roles.edit', $role->id) }}" title="Edit"
                                       class="w-8 h-8 flex items-center justify-center text-ink-muted hover:text-primary transition">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    @unless ($role->is_system)
                                        @include('manage.partials.delete', [
                                            'action' => route('manage.roles.destroy', $role->id),
                                            'confirm' => 'Remove the ' . $role->name . ' role?',
                                        ])
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
