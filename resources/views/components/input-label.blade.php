{{-- -------------------
     Form field label.
------------------- --}}
@props(['value' => null, 'required' => false])

<label {{ $attributes->merge(['class' => 'nm-label']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-primary">*</span>
    @endif
</label>
