{{-- ------------------- Ask for a reset code ------------------- --}}
@extends('manage.layouts.auth')

@section('title', 'Forgot your password')

@section('content')

    <p class="nm-eyebrow mb-2">Password reset</p>
    <h1 class="text-2xl nm-head">Forgot your password</h1>
    <p class="mt-1 text-sm text-ink-muted">
        Give us the email on your account and we will send a code.
    </p>

    @include('manage.auth.partials.errors')

    <form method="POST" action="{{ route('manage.auth.forgot-password.send') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="nm-label">Email address <span class="text-primary">*</span></label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted text-sm"></i>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="nm-input nm-input--icon-left">
            </div>
        </div>

        <button type="submit" class="nm-btn nm-btn--primary nm-btn--block nm-btn--lg">
            <i class="fas fa-paper-plane"></i> Send the code
        </button>

        <p class="text-center text-sm text-ink-muted">
            Remembered it?
            <a href="{{ route('manage.auth.login') }}" class="font-semibold text-primary hover:underline">Back to sign in</a>
        </p>

    </form>

@endsection
