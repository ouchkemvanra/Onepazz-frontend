@props([
    'variant' => 'dark',
    'size' => 32,
])

{{--
    There's no transparent white full wordmark asset yet, only an icon-only
    white PNG. So variant="white" renders icon-only — callers on dark/teal
    backgrounds that need the "OnePazz" text should keep their own text span
    next to <x-logo variant="white">.
--}}
@php
    $src = $variant === 'white' ? asset('images/logo.png') : asset('images/logo-dark.png');
@endphp

<img
    src="{{ $src }}"
    alt="OnePazz"
    {{ $attributes->merge(['class' => 'w-auto object-contain', 'style' => "height: {$size}px"]) }}
>
