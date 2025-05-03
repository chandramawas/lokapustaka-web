@props(['height' => '18px', 'variant' => 'outline', 'class' => ''])

@php
    $variants = [
        'outline' => 'm354-287 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-350Z',
        'filled' => 'm233-120 65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Z',
    ]
@endphp

<svg xmlns="http://www.w3.org/2000/svg" height="{{ $height }}" viewBox="0 -960 960 960" fill="currentColor"
    class="{{ $class }}">
    <path d="{{ $variants[$variant] }}" />
</svg>