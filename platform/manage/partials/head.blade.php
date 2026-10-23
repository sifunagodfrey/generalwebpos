{{-- -------------------
     Page header.

     @include('manage.partials.head', [
         'title'   => 'Page title',
         'lead'    => 'One line of context',            // optional
         'crumbs'  => ['Section' => route(...), 'Page' => null],  // optional, null means the current page
         'actions' => '<a ...>Button</a>',               // optional, raw HTML
     ])
------------------- --}}
@php
    $lead    = $lead ?? null;
    $crumbs  = $crumbs ?? [];
    $actions = $actions ?? null;
@endphp

<div class="mb-6">

    @if (! empty($crumbs))
        @php $lastLabel = array_key_last($crumbs); @endphp
        <nav class="nm-crumbs mb-3" aria-label="Breadcrumb">
            <a href="{{ route('manage.dashboard.index') }}" class="nm-crumb">Admin</a>
            @foreach ($crumbs as $label => $url)
                @if ($url)
                    <a href="{{ $url }}" class="nm-crumb">{{ $label }}</a>
                @elseif ($label === $lastLabel)
                    <span class="nm-crumb nm-crumb--current">{{ $label }}</span>
                @else
                    <span class="nm-crumb">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
            <h1 class="font-heading font-medium text-2xl text-ink leading-tight">{{ $title }}</h1>
            @if ($lead)
                <p class="text-sm text-ink-muted mt-1">{{ $lead }}</p>
            @endif
        </div>

        @if ($actions)
            <div class="flex items-center gap-2 shrink-0">
                {!! $actions !!}
            </div>
        @endif
    </div>

</div>
