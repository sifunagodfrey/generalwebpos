{{-- ------------------- Edit a staff account ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Edit ' . $user->name)

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Edit ' . $user->name,
        'crumbs' => ['Staff & Agents' => route('manage.system-users.index'), $user->name => route('manage.system-users.show', $user->id), 'Edit' => null],
    ])

    @php $userRoleIds = $user->roles->pluck('id')->all(); @endphp

    <form method="POST" action="{{ route('manage.system-users.update', $user->id) }}" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')

        <div class="nm-panel">
            <div class="nm-panel__head">Account</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'first_name', 'label' => 'First name', 'required' => true, 'value' => $user->first_name,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'last_name', 'label' => 'Last name', 'value' => $user->last_name,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'value' => $user->email,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'phone', 'label' => 'Phone', 'value' => $user->phone,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Password</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'password', 'label' => 'New password', 'type' => 'password',
                    'help' => 'Leave empty to keep the current password.', 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'password_confirmation', 'label' => 'Confirm new password', 'type' => 'password', 'value' => null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Roles</div>
            <div class="nm-panel__body">
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach ($roles as $role)
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                   @checked(in_array($role->id, $userRoleIds, true)) class="accent-primary mt-0.5 w-4 h-4">
                            <span>
                                <span class="block text-sm font-semibold text-ink">{{ $role->name }}</span>
                                @if ($role->description)
                                    <span class="block text-xs text-ink-muted">{{ $role->description }}</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('roles')<p class="nm-error mt-3">{{ $message }}</p>@enderror
            </div>
        </div>

        @include('manage.partials.form.actions', [
            'label'  => 'Save changes',
            'cancel' => route('manage.system-users.show', $user->id),
        ])

    </form>

@endsection
