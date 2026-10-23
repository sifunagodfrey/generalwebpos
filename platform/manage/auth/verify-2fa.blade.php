{{-- -------------------
     Two factor verification. Six boxes that move focus as they are typed and
     accept a pasted code in one go.
------------------- --}}
@extends('manage.layouts.auth')

@section('title', 'Verify your identity')

@section('content')

    <p class="nm-eyebrow mb-2">Security check</p>
    <h1 class="text-2xl nm-head">Enter your code</h1>
    <p class="mt-1 text-sm text-ink-muted">
        We have sent a six digit code to your email address. It expires in ten minutes.
    </p>

    @include('manage.auth.partials.errors')

    <form method="POST" action="{{ route('manage.auth.verify2fa.submit') }}" class="mt-8 space-y-6"
          data-turbo="false"
          x-data="{
            digits: ['', '', '', '', '', ''],
            get combined() { return this.digits.join(''); },
            onInput(index, event) {
                const value = event.target.value.replace(/[^0-9]/g, '');
                this.digits[index] = value.slice(-1);
                if (this.digits[index] && index < 5) this.$refs['d' + (index + 1)].focus();
            },
            onKey(index, event) {
                if (event.key === 'Backspace' && !this.digits[index] && index > 0) {
                    this.$refs['d' + (index - 1)].focus();
                }
            },
            onPaste(event) {
                const text = (event.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, 6);
                text.split('').forEach((char, i) => this.digits[i] = char);
                if (text.length) this.$refs['d' + Math.min(text.length, 5)].focus();
            }
          }">
        @csrf

        <input type="hidden" name="code" x-model="combined">

        <div class="flex gap-2 justify-between" @paste.prevent="onPaste($event)">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1"
                       x-ref="d{{ $i }}"
                       x-model="digits[{{ $i }}]"
                       @input="onInput({{ $i }}, $event)"
                       @keydown="onKey({{ $i }}, $event)"
                       @if ($i === 0) autofocus @endif
                       class="w-full h-14 text-center font-heading font-extrabold text-xl border border-line focus:border-primary focus:outline-none">
            @endfor
        </div>

        <button type="submit" class="nm-btn nm-btn--primary nm-btn--block nm-btn--lg"
                :disabled="combined.length < 6">
            <i class="fas fa-check"></i> Verify and continue
        </button>

        <p class="text-center text-sm text-ink-muted">
            Wrong account?
            <a href="{{ route('manage.auth.login') }}" class="font-semibold text-primary hover:underline">Sign in again</a>
        </p>

    </form>

@endsection
