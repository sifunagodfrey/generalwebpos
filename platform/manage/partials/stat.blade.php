{{-- -------------------
     Figure tile. Squared, with a thick left rule in the tone given.

     @include('manage.partials.stat', [
         'label' => 'Sales Today (KES)',
         'value' => '12,500',
         'tone'  => 'primary',        // ink | primary | secondary
         'icon'  => 'fa-coins',       // optional
         'link'  => route(...),       // optional, makes the whole card clickable
         'trend' => 12.5,             // optional, percent change vs the prior equivalent period, positive or negative
     ])
------------------- --}}
@php
    $tone  = $tone ?? 'ink';
    $trend = $trend ?? null;
    $trendInverse = $trendInverse ?? false;
    $accentVar = $tone === 'primary' ? '--primary-color' : ($tone === 'secondary' ? '--secondary-color' : '--ink-color');
    $iconColor = $tone === 'primary' ? 'text-primary' : ($tone === 'secondary' ? 'text-secondary' : 'text-ink-muted');
@endphp

@php
    $trendBlock = null;
    if ($trend !== null) {
        $up = $trend >= 0;
        $good = $trendInverse ? ! $up : $up;
        $trendBlock = '<span class="inline-flex items-center gap-1 text-xs font-medium mt-1 ' . ($good ? 'text-secondary' : 'text-primary') . '">'
            . '<i class="fas ' . ($up ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down') . ' text-[10px]"></i>'
            . ($up ? '+' : '') . number_format($trend, $trend != 0 && abs($trend) < 10 ? 1 : 0) . '%'
            . '</span>';
    }
@endphp

@if (isset($link))

    <a href="{{ $link }}" class="block border border-l-4 nm-surface rounded-card p-4 transition hover:shadow-md"
       style="border-left-color: var({{ $accentVar }})">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">{{ $label }}</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none truncate">{{ $value }}</p>
                {!! $trendBlock !!}
            </div>

            <div class="flex flex-col items-end gap-2 shrink-0">
                @isset($icon)
                    <i class="fas {{ $icon }} text-lg {{ $iconColor }}"></i>
                @endisset
                <i class="fas fa-arrow-right text-xs text-primary"></i>
            </div>
        </div>
    </a>

@else

    <div class="border border-l-4 nm-surface rounded-card p-4" style="border-left-color: var({{ $accentVar }})">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[11px] font-ui font-semibold uppercase tracking-label text-ink-muted">{{ $label }}</p>
                <p class="font-heading font-medium text-2xl text-ink mt-1 leading-none truncate">{{ $value }}</p>
                {!! $trendBlock !!}
            </div>
            @isset($icon)
                <i class="fas {{ $icon }} text-lg shrink-0 {{ $iconColor }}"></i>
            @endisset
        </div>
    </div>

@endif
