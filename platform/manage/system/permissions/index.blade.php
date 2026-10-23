{{-- -------------------
     Permission matrix.
     Roles down the side, permissions across, saved in one submit. The super
     administrator column is fixed since that role always holds everything.
------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Permissions')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Permissions',
        'lead'   => 'Tick what each role can do, then save once at the bottom.',
        'crumbs' => ['Roles & Permission' => route('manage.roles.index'), 'Permissions' => null],
    ])

    <form method="POST" action="{{ route('manage.permissions.update') }}" class="space-y-8">
        @csrf

        @foreach ($permissions as $module => $items)
            <div class="nm-panel">
                <div class="nm-panel__head">{{ Str::headline($module ?: 'General') }}</div>
                <div class="overflow-x-auto">
                    <table class="nm-table">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                @foreach ($roles as $role)
                                    <th class="text-center">{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $permission)
                                <tr>
                                    <td class="text-sm text-ink">
                                        {{ $permission->name }}
                                        @if ($permission->description)
                                            <span class="block text-xs text-ink-muted">{{ $permission->description }}</span>
                                        @endif
                                    </td>
                                    @foreach ($roles as $role)
                                        <td class="text-center">
                                            @if ($role->slug === 'super_admin')
                                                <i class="fas fa-check text-secondary"></i>
                                            @else
                                                <input type="checkbox"
                                                       name="roles[{{ $role->id }}][]" value="{{ $permission->id }}"
                                                       @checked($role->permissions->contains('id', $permission->id))
                                                       class="accent-primary w-4 h-4">
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @include('manage.partials.form.actions', ['label' => 'Save permissions'])

    </form>

@endsection
