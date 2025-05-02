@props(['color' => 'primary', 'href' => null])

@php
    $colors = [
        'primary' => 'bg-primary dark:bg-primary-dark text-on-primary dark:text-on-primary-dark',
        'secondary' => 'bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark',
        'tertiary' => 'bg-tertiary dark:bg-tertiary-dark text-on-tertiary dark:text-on-tertiary-dark',
    ]
@endphp

<a @if ($href) href="{{ $href }}" @endif class="font-medium text-label w-fit px-2 py-0.5 rounded-full animate-pulse {{ $colors[$color] }}">
    {{ $slot }}
</a>