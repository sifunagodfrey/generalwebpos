{{-- -------------------
     Form field.

     One include covers every control the admin panel uses, so a change to how
     errors or help text render lands everywhere at once.

     name, label, type, value, options, help, required, placeholder, attrs
------------------- --}}
@php
    $type        = $type ?? 'text';
    $value       = old($name, $value ?? null);
    $required    = $required ?? false;
    $placeholder = $placeholder ?? '';
    $attrs       = $attrs ?? '';
    $id          = str_replace(['[', ']', '.'], '_', $name);
    $invalid     = $errors->has($name);
@endphp

<div class="{{ $wrapper ?? '' }}">

    @if ($type !== 'checkbox')
        <label for="{{ $id }}" class="nm-label">
            {{ $label }}@if ($required)<span class="text-primary ml-0.5">*</span>@endif
        </label>
    @endif

    @if ($type === 'textarea')

        <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows ?? 4 }}"
                  placeholder="{{ $placeholder }}" @if ($required) required @endif
                  class="nm-textarea {{ $invalid ? 'border-primary' : '' }}" {!! $attrs !!}>{{ $value }}</textarea>

    @elseif ($type === 'select')

        <select name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif
                class="nm-select {{ $invalid ? 'border-primary' : '' }}" {!! $attrs !!}>
            @if (isset($blank))
                <option value="">{{ $blank }}</option>
            @endif
            @foreach (($options ?? []) as $key => $text)
                <option value="{{ $key }}" @selected((string) $value === (string) $key)>{{ $text }}</option>
            @endforeach
        </select>

    @elseif ($type === 'checkbox')

        <label for="{{ $id }}" class="flex items-start gap-3 cursor-pointer select-none">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="1"
                   @checked(filter_var($value, FILTER_VALIDATE_BOOLEAN)) class="accent-primary mt-0.5 w-4 h-4" {!! $attrs !!}>
            <span>
                <span class="block text-sm font-semibold text-ink">{{ $label }}</span>
                @isset($help)<span class="block text-xs text-ink-muted mt-0.5">{{ $help }}</span>@endisset
            </span>
        </label>

    @elseif ($type === 'file')

        <input type="file" name="{{ $name }}" id="{{ $id }}"
               class="nm-input file:mr-3 file:border-0 file:bg-overlay file:text-white file:px-3 file:py-1.5 file:text-xs file:font-ui file:font-bold file:uppercase file:tracking-label"
               {!! $attrs !!}>

        @isset($current)
            @if ($current)
                <div class="mt-2 flex items-center gap-3">
                    <img src="{{ Str::startsWith($current, 'http') ? $current : asset($current) }}" alt=""
                         class="w-16 h-16 object-cover border border-line">
                    <span class="text-xs text-ink-muted">Leave the field empty to keep this one.</span>
                </div>
            @endif
        @endisset

    @elseif ($type === 'color')

        <div class="flex items-center gap-2">
            <input type="color" name="{{ $name }}" id="{{ $id }}" value="{{ $value ?: '#BC2925' }}"
                   class="w-12 h-10 border border-line p-1 cursor-pointer">
            <input type="text" value="{{ $value }}" readonly onclick="this.select()"
                   class="nm-input font-mono text-xs"
                   oninput="document.getElementById('{{ $id }}').value = this.value">
        </div>

    @else

        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
               placeholder="{{ $placeholder }}" @if ($required) required @endif
               class="nm-input {{ $invalid ? 'border-primary' : '' }}" {!! $attrs !!}>

    @endif

    @if ($type !== 'checkbox')
        @isset($help)<p class="nm-help">{{ $help }}</p>@endisset
    @endif

    @error($name)<p class="nm-error">{{ $message }}</p>@enderror

</div>
