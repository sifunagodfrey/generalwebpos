{{-- -------------------
     Outline action button. Quiet alternative to the primary action.
------------------- --}}
<button {{ $attributes->merge([
    'type'  => 'button',
    'class' => 'nm-btn nm-btn--outline',
]) }}>
    {{ $slot }}
</button>
