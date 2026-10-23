{{-- ------------------- Set a new password ------------------- --}}
@extends('manage.layouts.auth')

@section('title', 'Set a new password')

@section('content')

    <p class="nm-eyebrow mb-2">Password reset</p>
    <h1 class="text-2xl nm-head">Set a new password</h1>
    <p class="mt-1 text-sm text-ink-muted">At least eight characters, with a letter and a number.</p>

    @include('manage.auth.partials.errors')

    <form method="POST" action="{{ route('manage.auth.forgot-password.reset.submit') }}" class="mt-8 space-y-5"
          x-data="{ show: false }">
        @csrf

        <div>
            <label for="password" class="nm-label">New password <span class="text-primary">*</span></label>
            <div class="relative">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted text-sm"></i>
                <input :type="show ? 'text' : 'password'" name="password" id="password" required autofocus
                       class="nm-input nm-input--icon-left nm-input--icon-right">
                <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted hover:text-primary transition"
                        aria-label="Show or hide the password">
                    <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="nm-label">Confirm it <span class="text-primary">*</span></label>
            <div class="relative">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted text-sm"></i>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                       class="nm-input nm-input--icon-left">
            </div>
        </div>

        <button type="submit" class="nm-btn nm-btn--primary nm-btn--block nm-btn--lg">
            <i class="fas fa-check"></i> Save the new password
        </button>

        <p class="text-center text-sm text-ink-muted">
            <a href="{{ route('manage.auth.login') }}" class="font-semibold text-primary hover:underline">Back to sign in</a>
        </p>

    </form>

@endsection
