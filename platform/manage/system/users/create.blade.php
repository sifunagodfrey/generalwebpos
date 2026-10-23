{{-- ------------------- Add a staff account ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Add staff')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Add staff',
        'crumbs' => ['Staff & Agents' => route('manage.system-users.index'), 'Add' => null],
    ])

    <form method="POST" action="{{ route('manage.system-users.store') }}" class="max-w-2xl space-y-6">
        @csrf

        <div class="nm-panel">
            <div class="nm-panel__head">Account</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'first_name', 'label' => 'First name', 'required' => true, 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'last_name', 'label' => 'Last name', 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'phone', 'label' => 'Phone', 'value' => null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Password</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true,
                    'help' => 'At least 8 characters, with letters and numbers.', 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'password_confirmation', 'label' => 'Confirm password', 'type' => 'password', 'required' => true, 'value' => null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Roles</div>
            <div class="nm-panel__body">
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach ($roles as $role)
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="accent-primary mt-0.5 w-4 h-4">
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
            'label'  => 'Save staff account',
            'cancel' => route('manage.system-users.index'),
        ])

    </form>

@endsection
