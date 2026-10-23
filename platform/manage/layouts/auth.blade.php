{{-- -------------------
     Sign in layout.

     A plain, centered light page, deliberately unbranded as "admin"
     anywhere visible, since a sign in screen is a reasonable place for an
     attacker to go looking, no need to make this look like the interesting
     one.
------------------- --}}
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Sign in') | Suweka POS</title>

    <link rel="icon" type="image/png" href="{{ asset('images/suweka-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

</head>

<body class="h-full bg-steel">

    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            <div class="mb-10 text-center">
                <img src="{{ asset('images/suweka-logo.png') }}"
                     alt="Suweka POS" class="h-11 w-auto mx-auto">
            </div>

            <div class="nm-panel">
                <div class="nm-panel__body">
                    @yield('content')
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-ink-muted">
                &copy; {{ date('Y') }} Suweka POS
            </p>

        </div>
    </div>

    @stack('scripts')

</body>

</html>
