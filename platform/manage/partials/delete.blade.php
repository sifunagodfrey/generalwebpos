{{-- -------------------
     Delete control. Confirms first, and says what is being removed rather
     than asking a generic are you sure.
------------------- --}}
<form method="POST" action="{{ $action }}" class="inline"
      onsubmit="return confirm('{{ $confirm ?? 'Remove this permanently? This cannot be undone.' }}')">
    @csrf
    @method($method ?? 'DELETE')
    <button type="submit" title="{{ $label ?? 'Delete' }}"
            class="{{ $class ?? 'w-8 h-8 flex items-center justify-center text-ink-muted hover:text-primary transition' }}">
        <i class="fas {{ $icon ?? 'fa-trash-can' }}"></i>
        @isset($text)<span class="ml-1.5">{{ $text }}</span>@endisset
    </button>
</form>
