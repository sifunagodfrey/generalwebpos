{{-- ------------------- Add a role ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Add a role')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Add a role',
        'crumbs' => ['Roles & Permission' => route('manage.roles.index'), 'Add' => null],
    ])

    <form method="POST" action="{{ route('manage.roles.store') }}" class="max-w-3xl space-y-6">
        @csrf

        <div class="nm-panel">
            <div class="nm-panel__head">The role</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'name', 'label' => 'Name', 'required' => true, 'value' => null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'description', 'label' => 'Description', 'wrapper' => 'sm:col-span-2', 'value' => null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Permissions</div>
            <div class="nm-panel__body space-y-6">
                @foreach ($permissions as $module => $items)
                    <div>
                        <p class="text-xs font-ui font-bold uppercase tracking-label text-ink-muted mb-2">{{ Str::headline($module ?: 'General') }}</p>
                        <div class="grid sm:grid-cols-2 gap-2.5">
                            @foreach ($items as $permission)
                                <label class="flex items-start gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="accent-primary mt-0.5 w-4 h-4">
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

        @include('manage.partials.form.actions', [
            'label'  => 'Save role',
            'cancel' => route('manage.roles.index'),
        ])

    </form>

@endsection
