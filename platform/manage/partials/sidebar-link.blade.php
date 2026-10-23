{{-- ------------------- One sidebar link ------------------- --}}
@php $active = request()->routeIs($item['match']); @endphp

<a href="{{ route($item['route']) }}"
   class="group/link relative flex items-center gap-3 px-4 py-2.5 text-sm transition
          {{ $active ? 'bg-primary text-white font-semibold' : 'text-ink-muted hover:bg-steel hover:text-ink' }}">

    <i class="fas {{ $item['icon'] }} w-4 text-center shrink-0 {{ $active ? 'text-white' : '' }}"></i>

    <span x-show="!iconOnly" x-cloak class="truncate flex-1">{{ $item['label'] }}</span>

    @if (! empty($item['badge']))
        <span x-show="!iconOnly" x-cloak
              class="shrink-0 min-w-[18px] h-[18px] px-1 text-[10px] font-bold flex items-center justify-center
                     {{ $active ? 'bg-white text-primary' : 'bg-primary text-white' }}">
            {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
        </span>
    @endif

    {{-- Hover label, collapsed state only --}}
    <span x-show="iconOnly" x-cloak
          class="pointer-events-none absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap
                 bg-ink text-white text-xs font-semibold px-2.5 py-1.5 opacity-0 group-hover/link:opacity-100
                 transition-opacity duration-150 z-50 shadow-lg">
        {{ $item['label'] }}
    </span>
</a>
