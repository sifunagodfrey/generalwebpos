{{-- ------------------- Enter the reset code ------------------- --}}
@extends('manage.layouts.auth')

@section('title', 'Enter your reset code')

@section('content')

    <p class="nm-eyebrow mb-2">Password reset</p>
    <h1 class="text-2xl nm-head">Enter the code</h1>
    <p class="mt-1 text-sm text-ink-muted">Type the six digit code we sent you.</p>

    @include('manage.auth.partials.errors')

    <form method="POST" action="{{ route('manage.auth.forgot-password.verify.submit') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="code" class="nm-label">Reset code <span class="text-primary">*</span></label>
            <input type="text" name="code" id="code" inputmode="numeric" maxlength="6" required autofocus
                   placeholder="000000"
                   class="nm-input text-center font-heading font-extrabold text-xl tracking-[0.4em]">
        </div>

        <button type="submit" class="nm-btn nm-btn--primary nm-btn--block nm-btn--lg">
            <i class="fas fa-check"></i> Verify the code
        </button>

        <p class="text-center text-sm text-ink-muted">
            Code expired?
            <a href="{{ route('manage.auth.forgot-password') }}" class="font-semibold text-primary hover:underline">Ask for another</a>
        </p>

    </form>

@endsection
