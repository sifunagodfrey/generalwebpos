{{-- -------------------
     Settings navigation. Grouped so the things changed often sit at the top
     and the connection settings that are set once and left alone sit at
     the bottom.
------------------- --}}
@php
    $groups = [
        'The business' => [
            ['label' => 'General', 'icon' => 'fa-building', 'route' => 'manage.settings.general.index'],
            ['label' => 'Email', 'icon' => 'fa-envelope', 'route' => 'manage.settings.email.index'],
            ['label' => 'Security', 'icon' => 'fa-shield-halved', 'route' => 'manage.settings.security.index'],
        ],
        'Call center' => [
            ['label' => 'Voice provider', 'icon' => 'fa-phone-volume', 'route' => 'manage.settings.voice-provider.index'],
            ['label' => 'Business hours', 'icon' => 'fa-clock', 'route' => 'manage.settings.business-hours.index'],
            ['label' => 'Hold media', 'icon' => 'fa-music', 'route' => 'manage.settings.hold-media.index'],
        ],
    ];
@endphp

<div class="nm-panel lg:sticky lg:top-6">
    @foreach ($groups as $heading => $items)
        <p class="px-4 pt-4 pb-1.5 text-[10px] font-ui font-bold uppercase tracking-label text-ink-muted">
            {{ $heading }}
        </p>
        @foreach ($items as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm transition
                      {{ $active ? 'bg-primary text-white font-semibold' : 'text-ink hover:bg-steel hover:text-primary' }}">
                <i class="fas {{ $item['icon'] }} w-4 text-center text-xs"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    @endforeach
</div>
