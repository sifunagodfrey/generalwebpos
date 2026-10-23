<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'General POS') | General POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-steel text-ink min-h-screen">
    <div class="min-h-screen lg:flex">
        <aside class="lg:w-64 shrink-0 border-r border-line">
            @include('manage.partials.sidebar')
        </aside>
        <div class="flex-1 min-w-0">
            @include('manage.partials.topbar')
            <main class="p-5 lg:p-8">
                @if(session('success'))<div class="mb-4 p-3 rounded bg-green-100 text-green-900">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="mb-4 p-3 rounded bg-red-100 text-red-900">{{ session('error') }}</div>@endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
