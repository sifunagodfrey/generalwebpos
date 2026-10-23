{{-- -------------------
     Multi line text control.
------------------- --}}
@props(['disabled' => false, 'rows' => 4])

<textarea @disabled($disabled) rows="{{ $rows }}" {{ $attributes->merge(['class' => 'nm-textarea']) }}>{{ $slot }}</textarea>
