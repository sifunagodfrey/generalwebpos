{{-- -------------------
     One sidebar nav item.

     A leaf renders as a single pill link. A parent renders as a pill
     button that expands an accordion of children when the sidebar is
     open, or a hover flyout listing those same children when the sidebar
     is collapsed to icons. Sub items have no icon of their own, only the
     parent carries one, matching the reference the client is redesigning
     against.
------------------- --}}
@if (empty($item['children']))

    @php $active = request()->routeIs($item['match']); @endphp

    <a href="{{ route($item['route']) }}"
       class="group/link relative flex items-center gap-3 px-3 py-2.5 mb-0.5 rounded-pill text-sm transition
              {{ $active ? 'nm-nav-active font-semibold' : 'text-ink-muted hover:bg-steel hover:text-ink' }}">

        <i class="fas {{ $item['icon'] }} w-4 text-center shrink-0"></i>

        <span x-show="!iconOnly" x-cloak class="truncate flex-1">{{ $item['label'] }}</span>

        @if (! empty($item['liveBadge']))
            @php $liveKey = $item['liveBadge']; @endphp
            <span x-show="!iconOnly" x-cloak
                  x-text="$store.liveStats.{{ $liveKey }}"
                  class="shrink-0 min-w-[20px] h-[18px] px-1.5 rounded-pill text-[10px] font-bold flex items-center justify-center
                         {{ $liveKey === 'agents_online'
                                ? ($active ? 'bg-white text-secondary' : 'bg-secondary-light text-secondary')
                                : ($liveKey === 'followups_due'
                                    ? ($active ? 'bg-white text-primary' : 'bg-primary text-white')
                                    : ($active ? 'bg-white text-ink' : 'bg-steel text-ink-muted')) }}">{{ $liveStats[$liveKey] ?? 0 }}</span>
        @elseif (! empty($item['badge']))
            <span x-show="!iconOnly" x-cloak
                  class="shrink-0 min-w-[18px] h-[18px] px-1 rounded-pill text-[10px] font-bold flex items-center justify-center
                         {{ $active ? 'bg-white text-ink' : 'bg-primary text-white' }}">
                {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
            </span>
        @endif

        {{-- Hover label, collapsed state only --}}
        <span x-show="iconOnly" x-cloak
              class="pointer-events-none absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap
                     bg-overlay text-white text-xs font-semibold px-3 py-1.5 rounded-pill opacity-0 group-hover/link:opacity-100
                     transition-opacity duration-150 z-50 shadow-lg">
            {{ $item['label'] }}
        </span>
    </a>

@else

    @php $isActive = collect($item['children'])->contains(fn ($child) => request()->routeIs($child['match'])); @endphp

    <div x-data="{ open: {{ $item['open'] ? 'true' : 'false' }} }" class="group/parent relative mb-0.5">

        <button type="button" @click="iconOnly ? null : (open = !open)"
                class="w-full relative flex items-center gap-3 px-3 py-2.5 rounded-pill text-sm transition
                       {{ $isActive ? 'nm-nav-active font-semibold' : 'text-ink-muted hover:bg-steel hover:text-ink' }}">

            <i class="fas {{ $item['icon'] }} w-4 text-center shrink-0"></i>

            <span x-show="!iconOnly" x-cloak class="truncate flex-1 text-left">{{ $item['label'] }}</span>

            <i x-show="!iconOnly" x-cloak class="fas fa-chevron-down text-[9px] transition-transform shrink-0"
               :class="open ? 'rotate-180' : ''"></i>

            {{-- Hover label, collapsed state only, only names the parent since
                 its children are already reachable through the flyout below --}}
            <span x-show="iconOnly" x-cloak
                  class="pointer-events-none absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap
                         bg-overlay text-white text-xs font-semibold px-3 py-1.5 rounded-pill opacity-0 group-hover/parent:opacity-100
                         transition-opacity duration-150 z-50 shadow-lg">
                {{ $item['label'] }}
            </span>
        </button>

        {{-- Expanded, a normal accordion of children under the parent --}}
        <div x-show="!iconOnly && open" x-collapse class="mt-0.5 space-y-0.5">
            @foreach ($item['children'] as $child)
                @php $childActive = request()->routeIs($child['match']); @endphp
                <a href="{{ route($child['route']) }}"
                   class="flex items-center gap-3 py-2 pl-11 pr-3 rounded-pill text-sm transition
                          {{ $childActive ? 'nm-nav-active--sub font-semibold' : 'text-ink-muted hover:text-ink hover:bg-steel' }}">
                    <span class="truncate flex-1">{{ $child['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Collapsed, one icon only, its children reachable through a hover flyout instead
             of being stacked as their own rows in the icon rail --}}
        <div x-show="iconOnly" x-cloak
             class="dropdown-panel invisible opacity-0 group-hover/parent:visible group-hover/parent:opacity-100
                    transition-opacity duration-150 absolute left-full top-0 ml-1 w-56 bg-surface
                    border border-line rounded-card shadow-lg z-50 py-2 overflow-hidden">
            <p class="px-4 py-1.5 text-[11px] font-ui font-bold uppercase tracking-label text-ink-muted">{{ $item['label'] }}</p>
            @foreach ($item['children'] as $child)
                @php $childActive = request()->routeIs($child['match']); @endphp
                <a href="{{ route($child['route']) }}"
                   class="flex items-center px-4 py-2 text-sm transition
                          {{ $childActive ? 'text-primary font-semibold' : 'text-ink hover:bg-steel hover:text-primary' }}">
                    <span class="truncate flex-1">{{ $child['label'] }}</span>
                </a>
            @endforeach
        </div>

    </div>

@endif
