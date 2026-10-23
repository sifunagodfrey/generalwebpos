{{-- -------------------
     Validation messages for a single field.
------------------- --}}
@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'nm-error space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
