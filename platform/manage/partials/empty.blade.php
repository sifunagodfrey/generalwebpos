{{-- ------------------- Empty state ------------------- --}}
<div class="border nm-surface rounded-card px-6 py-16 text-center">
    <i class="fas {{ $icon ?? 'fa-inbox' }} text-3xl text-ink-muted/30 mb-4"></i>
    <h3 class="font-heading font-bold text-ink">{{ $title ?? 'Nothing here yet' }}</h3>
    @isset($message)
        <p class="text-sm text-ink-muted mt-2 max-w-sm mx-auto">{{ $message }}</p>
    @endisset
    @isset($action)
        <div class="mt-6">{!! $action !!}</div>
    @endisset
</div>
