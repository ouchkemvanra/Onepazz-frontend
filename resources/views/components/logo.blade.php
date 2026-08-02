@props([
    'variant' => 'dark',
    'size' => 32,
])

{{--
    variant="dark"       — full color wordmark, for light backgrounds.
    variant="white"      — icon only, white, for dark backgrounds (pair with your own text).
    variant="white-full" — icon + "onepazz" wordmark, white, for dark backgrounds.
--}}
@php
    $src = match ($variant) {
        'white'      => asset('images/logo.png'),
        'white-full' => asset('images/logo-white-full.png'),
        default      => asset('images/logo-dark.png'),
    };
@endphp

<img
    src="{{ $src }}"
    alt="OnePazz"
    {{ $attributes->merge(['class' => 'w-auto object-contain', 'style' => "height: {$size}px"]) }}
>
