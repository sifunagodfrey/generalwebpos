{{-- ------------------- Sign in ------------------- --}}
@extends('manage.layouts.auth')

@section('title', 'Sign in')

@section('content')

    <h1 class="text-2xl nm-head">Sign in</h1>
    <p class="mt-1 text-sm text-ink-muted">Enter your details to continue.</p>

    @include('manage.auth.partials.errors')

    <form method="POST" action="{{ route('manage.auth.login.submit') }}" class="mt-8 space-y-5" data-turbo="false">
        @csrf

        <div>
            <label for="email" class="nm-label">Email address <span class="text-primary">*</span></label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted text-sm"></i>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       placeholder="you@example.com" class="nm-input nm-input--icon-left">
            </div>
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="nm-label">Password <span class="text-primary">*</span></label>
            <div class="relative">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted text-sm"></i>
                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                       class="nm-input nm-input--icon-left nm-input--icon-right">
                <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted hover:text-primary transition"
                        aria-label="Show or hide the password">
                    <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-ink-muted cursor-pointer">
                <input type="checkbox" name="remember" class="accent-primary w-4 h-4">
                Remember me
            </label>
            <a href="{{ route('manage.auth.forgot-password') }}" class="text-sm font-semibold text-primary hover:underline">
                Forgot your password?
            </a>
        </div>

        <button type="submit" class="nm-btn nm-btn--primary nm-btn--block nm-btn--lg">
            <i class="fas fa-right-to-bracket"></i> Sign in
        </button>

    </form>

@endsection
