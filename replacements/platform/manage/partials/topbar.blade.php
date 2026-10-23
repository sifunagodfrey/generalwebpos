<header class="h-16 border-b border-line px-5 flex items-center justify-between">
    <span class="font-semibold">@yield('title', 'Dashboard')</span>
    <div class="flex items-center gap-4">
        <span>{{ auth()->user()?->first_name }} {{ auth()->user()?->last_name }}</span>
        <form method="POST" action="{{ route('manage.auth.logout') }}">@csrf<button type="submit" class="underline">Sign out</button></form>
    </div>
</header>
