{{-- ------------------- Panel opening ------------------- --}}
<div class="nm-panel {{ $class ?? '' }}">
    @isset($heading)
        <div class="nm-panel__head flex items-center justify-between gap-3">
            <span>{{ $heading }}</span>
            @isset($headingAction){!! $headingAction !!}@endisset
        </div>
    @endisset
