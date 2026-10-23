<!--
    Shared error page body.
    Included by each status code view with a $code, $title, and $message.
-->
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $code }} | {{ $title }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="h-full bg-steel">

    <div class="min-h-screen flex items-center justify-center px-5">
        <div class="max-w-md w-full text-center">

            <span class="font-heading font-black text-primary leading-none text-7xl tracking-tight">{{ $code }}</span>

            <h1 class="text-xl font-heading font-extrabold text-ink mt-4 mb-2">{{ $title }}</h1>

            <p class="text-ink-muted text-sm leading-relaxed mb-8">{{ $message }}</p>

            <a href="{{ route('manage.auth.login') }}" class="nm-btn nm-btn--primary">
                <i class="fas fa-arrow-left"></i> Back to sign in
            </a>

        </div>
    </div>

</body>

</html>
