{{-- -------------------
     Primary action button. Solid brand red with squared corners.
------------------- --}}
<button {{ $attributes->merge([
    'type'  => 'submit',
    'class' => 'nm-btn nm-btn--primary',
]) }}>
    {{ $slot }}
</button>
