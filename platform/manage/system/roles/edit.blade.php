{{-- ------------------- Edit a role ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Edit ' . $role->name)

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Edit ' . $role->name,
        'crumbs' => ['Roles & Permission' => route('manage.roles.index'), $role->name => null],
    ])

    @php $rolePermissionIds = $role->permissions->pluck('id')->all(); @endphp

    <form method="POST" action="{{ route('manage.roles.update', $role->id) }}" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="nm-panel">
            <div class="nm-panel__head">The role</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'name', 'label' => 'Name', 'required' => true, 'value' => $role->name,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'description', 'label' => 'Description', 'wrapper' => 'sm:col-span-2', 'value' => $role->description,
                ])

            </div>
        </div>

        @if ($role->slug === 'super_admin')

            <div class="nm-panel">
                <div class="nm-panel__body">
                    <p class="text-sm text-ink-muted">
                        <i class="fas fa-circle-info text-primary mr-1"></i>
                        The super administrator role always holds every permission and cannot be limited here.
                    </p>
                </div>
            </div>

        @else

            <div class="nm-panel">
                <div class="nm-panel__head">Permissions</div>
                <div class="nm-panel__body space-y-6">
                    @foreach ($permissions as $module => $items)
                        <div>
                            <p class="text-xs font-ui font-bold uppercase tracking-label text-ink-muted mb-2">{{ Str::headline($module ?: 'General') }}</p>
                            <div class="grid sm:grid-cols-2 gap-2.5">
                                @foreach ($items as $permission)
                                    <label class="flex items-start gap-2.5 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                               @checked(in_array($permission->id, $rolePermissionIds, true)) class="accent-primary mt-0.5 w-4 h-4">
                                        <span>
                                            <span class="block text-sm text-ink">{{ $permission->name }}</span>
                                            @if ($permission->description)
                                                <span class="block text-xs text-ink-muted">{{ $permission->description }}</span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @endif

        @include('manage.partials.form.actions', [
            'label'  => 'Save changes',
            'cancel' => route('manage.roles.index'),
        ])

    </form>

@endsection
