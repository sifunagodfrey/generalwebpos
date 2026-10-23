{{-- ------------------- Security ------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Security')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Security',
        'lead'   => 'How people sign in and how long they stay signed in.',
        'crumbs' => ['Configurations' => route('manage.settings.general.index'), 'Security' => null],
    ])

    <div class="grid lg:grid-cols-4 gap-6 items-start">

        <div class="lg:col-span-1">
            @include('manage.settings.partials.nav')
        </div>

        <form method="POST" action="{{ route('manage.settings.security.update') }}" class="lg:col-span-3 space-y-6">
            @csrf


        <div class="nm-panel">
            <div class="nm-panel__head">Sign in</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'two_factor_admin',
                    'label' => 'Ask administrators for an emailed code',
                    'type' => 'checkbox',
                    'help' => 'Leave this off until you have seen a test email arrive, or a mail fault locks everyone out.',
                    'value' => $values['two_factor_admin'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'two_factor_customer',
                    'label' => 'Ask customers for an emailed code',
                    'type' => 'checkbox',
                    'value' => $values['two_factor_customer'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'session_timeout_minutes',
                    'label' => 'Sign out after',
                    'type' => 'number',
                    'help' => 'Minutes of inactivity.',
                    'attrs' => 'min="5" max="1440"',
                    'value' => $values['session_timeout_minutes'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'max_login_attempts',
                    'label' => 'Attempts before a lockout',
                    'type' => 'number',
                    'attrs' => 'min="3" max="20"',
                    'value' => $values['max_login_attempts'] ?? null,
                ])

            </div>
        </div>

        <div class="nm-panel">
            <div class="nm-panel__head">Accounts</div>
            <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                @include('manage.partials.form.field', [
                    'name' => 'allow_guest_checkout',
                    'label' => 'Allow checkout without an account',
                    'type' => 'checkbox',
                    'help' => 'Most roofing buyers order once. Switching this off will cost you orders.',
                    'value' => $values['allow_guest_checkout'] ?? null,
                ])

                @include('manage.partials.form.field', [
                    'name' => 'allow_registration',
                    'label' => 'Allow customers to register',
                    'type' => 'checkbox',
                    'value' => $values['allow_registration'] ?? null,
                ])

            </div>
        </div>


            @include('manage.partials.form.actions', ['label' => 'Save settings'])

        </form>

    </div>

@endsection
