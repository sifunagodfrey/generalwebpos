{{-- -------------------
     Text input.
------------------- --}}
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'nm-input']) }}>
