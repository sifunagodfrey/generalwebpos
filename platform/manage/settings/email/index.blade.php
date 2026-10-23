{{-- -------------------
     Outgoing mail.

     A test message is the only way to know this is right, so the test sits
     on the same screen rather than somewhere else.
------------------- --}}
@extends('manage.layouts.app')

@section('title', 'Email settings')

@section('content')

    @include('manage.partials.head', [
        'title'  => 'Email settings',
        'lead'   => 'Sign in codes, order copies and quotations all go out through this.',
        'crumbs' => ['Configurations' => route('manage.settings.general.index'), 'Email' => null],
    ])

    <div class="grid lg:grid-cols-4 gap-6 items-start">

        <div class="lg:col-span-1">@include('manage.settings.partials.nav')</div>

        <div class="lg:col-span-3 space-y-6">

            <form method="POST" action="{{ route('manage.settings.email.update') }}" class="space-y-6">
                @csrf

                <div class="nm-panel">
                    <div class="nm-panel__head">The mail server</div>
                    <div class="nm-panel__body grid sm:grid-cols-2 gap-5">

                        @include('manage.partials.form.field', [
                            'name' => 'driver', 'label' => 'Method', 'type' => 'select', 'required' => true,
                            'options' => ['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Write to the log only'],
                            'value' => $settings->driver,
                            'help' => 'Log writes messages to storage/logs instead of sending them, which is useful while testing.',
                        ])

                        @include('manage.partials.form.field', [
                            'name' => 'host', 'label' => 'Host', 'value' => $settings->host,
                            'placeholder' => 'mail.nationmabati.co.ke',
                        ])

                        @include('manage.partials.form.field', [
                            'name' => 'port', 'label' => 'Port', 'type' => 'number', 'value' => $settings->port,
                            'help' => '587 with TLS, or 465 with SSL.',
                        ])

                        @include('manage.partials.form.field', [
                            'name' => 'encryption', 'label' => 'Encryption', 'type' => 'select',
                            'options' => ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'],
                            'value' => $settings->encryption,
                        ])

                        @include('manage.partials.form.field', [
                            'name' => 'username', 'label' => 'Username', 'value' => $settings->username,
                            'placeholder' => 'noreply@nationmabati.co.ke',
                        ])

                        @include('manage.partials.form.field', [
                            'name' => 'password', 'label' => 'Password', 'type' => 'password', 'value' => null,
                            'help' => 'Leave empty to keep the password already saved.',
                        ])

                    </div>
                </div>

                <div class="nm-panel">
                    <div class="nm-panel__head">Who messages come from</div>
                    <div class="nm-panel__body grid sm:grid-cols-2 gap-5">
                        @include('manage.partials.form.field', [
                            'name' => 'from_address', 'label' => 'From address', 'type' => 'email',
                            'value' => $settings->from_address,
                            'help' => 'Use an address on your own domain, or messages will land in spam.',
                        ])
                        @include('manage.partials.form.field', [
                            'name' => 'from_name', 'label' => 'From name', 'value' => $settings->from_name,
                        ])
                        @include('manage.partials.form.field', [
                            'name' => 'is_active', 'label' => 'Send using these settings', 'type' => 'checkbox',
                            'value' => $settings->is_active, 'wrapper' => 'sm:col-span-2',
                            'help' => 'Switched off, the application falls back to whatever is in the environment file.',
                        ])
                    </div>
                </div>

                @include('manage.partials.form.actions', ['label' => 'Save settings'])

            </form>

            {{-- Test --}}
            <form method="POST" action="{{ route('manage.settings.email.test') }}">
                @csrf
                <div class="nm-panel border-t-4 border-t-secondary">
                    <div class="nm-panel__head">Send a test message</div>
                    <div class="nm-panel__body">
                        <p class="nm-help mb-4">
                            Save first, then send one to yourself. If it fails, the reason is shown here rather
                            than hidden in a log.
                        </p>
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="flex-1 min-w-[240px]">
                                <label class="nm-label">Send to</label>
                                <input type="email" name="to" required class="nm-input"
                                       value="{{ auth()->user()?->email }}">
                            </div>
                            <button type="submit" class="nm-btn nm-btn--ink">
                                <i class="fas fa-paper-plane"></i> Send test
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
