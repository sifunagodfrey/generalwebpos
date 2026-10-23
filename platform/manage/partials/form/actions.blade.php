{{-- ------------------- Form footer ------------------- --}}
<div class="flex flex-wrap items-center gap-3 pt-5 mt-5 border-t border-line">
    <button type="submit" class="nm-btn nm-btn--primary">
        <i class="fas fa-check"></i> {{ $label ?? 'Save' }}
    </button>
    @isset($cancel)
        <a href="{{ $cancel }}" class="nm-btn nm-btn--ghost">Cancel</a>
    @endisset
    @isset($extra){!! $extra !!}@endisset
</div>
