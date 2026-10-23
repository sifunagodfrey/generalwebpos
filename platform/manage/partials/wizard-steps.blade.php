{{-- -------------------
     Wizard step indicator.

     @include('manage.partials.wizard-steps', [
         'steps' => [
             ['label' => 'Customer', 'url' => route(...)],   // url omitted or null for a step not yet reachable
             ['label' => 'Lines',    'url' => route(...)],
             ['label' => 'Details',  'url' => route(...)],
             ['label' => 'Review',   'url' => null],
         ],
         'current' => 1, // zero based index of the active step
     ])
------------------- --}}
<div class="flex items-center mb-6 overflow-x-auto no-scrollbar">
    @foreach ($steps as $i => $step)
        @php
            $done = $i < $current;
            $active = $i === $current;
            $reachable = $done && ! empty($step['url']);
        @endphp

        <div class="flex items-center shrink-0">

            @if ($reachable)
                <a href="{{ $step['url'] }}" class="flex items-center gap-2 group">
                    <span class="w-7 h-7 flex items-center justify-center text-xs font-bold shrink-0 bg-secondary text-white">
                        <i class="fas fa-check text-[10px]"></i>
                    </span>
                    <span class="text-sm font-semibold text-ink-muted group-hover:text-ink transition whitespace-nowrap">{{ $step['label'] }}</span>
                </a>
            @else
                <span class="flex items-center gap-2">
                    <span class="w-7 h-7 flex items-center justify-center text-xs font-bold shrink-0
                                 {{ $active ? 'bg-primary text-white' : ($done ? 'bg-secondary text-white' : 'bg-steel text-ink-muted') }}">
                        {{ $done ? '' : $i + 1 }}
                        @if ($done)<i class="fas fa-check text-[10px]"></i>@endif
                    </span>
                    <span class="text-sm font-semibold whitespace-nowrap {{ $active ? 'text-ink' : 'text-ink-muted' }}">{{ $step['label'] }}</span>
                </span>
            @endif

        </div>

        @if (! $loop->last)
            <div class="w-8 sm:w-16 h-px shrink-0 mx-2 {{ $i < $current ? 'bg-secondary' : 'bg-line' }}"></div>
        @endif
    @endforeach
</div>
