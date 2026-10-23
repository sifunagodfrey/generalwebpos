<nav class="p-4 space-y-2">
    <a href="{{ route('manage.dashboard.index') }}" class="flex items-center gap-3 p-3 font-bold text-primary" aria-label="Suweka POS dashboard">
        <img src="{{ asset('images/suweka-logo.png') }}" alt="Suweka POS" class="h-10 w-auto max-w-[170px] object-contain">
    </a>
    <a href="{{ route('manage.dashboard.index') }}" class="block p-3 rounded hover:bg-primary/10 text-ink">Dashboard</a>
    {{-- POS modules will be added here. --}}
</nav>
