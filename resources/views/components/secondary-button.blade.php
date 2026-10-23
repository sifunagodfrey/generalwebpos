{{-- -------------------
     Secondary action button. Teal, used beside a primary action.
------------------- --}}
<button {{ $attributes->merge([
    'type'  => 'button',
    'class' => 'nm-btn nm-btn--secondary',
]) }}>
    {{ $slot }}
</button>
