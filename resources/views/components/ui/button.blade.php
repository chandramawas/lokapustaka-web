@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
])

@php
    $base = 'flex items-center justify-center rounded-md font-medium text-center transition';

    $variants = [
        'primary' => "px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark bg-primary text-on-primary hover:bg-primary/80 hover:text-on-primary/80",
        'primary-lg' => "px-4 py-2 text-body-sm md:text-body-md border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark bg-primary text-on-primary hover:bg-primary/80 hover:text-on-primary/80",
        'secondary' => "px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark hover:bg-surface/80 dark:hover:bg-surface-dark/80 hover:text-on-surface/80 dark:hover:text-on-surface-dark/80",
        'secondary-lg' => "px-4 py-2 text-body-sm md:text-body-md border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark hover:bg-surface/80 dark:hover:bg-surface-dark/80 hover:text-on-surface/80 dark:hover:text-on-surface-dark/80",
        'outline' => "px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark",
        'outline-lg' => "px-4 py-2 text-body-sm md:text-body-md border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark",
        'text' => "px-3 py-1 text-label hover:text-primary/80 dark:hover:text-primary-dark/80",
        'text-lg' => "px-4 py-2 text-body-sm md:text-body-md hover:text-primary/80 dark:hover:text-primary-dark/80",
    ];

    $disabledStyle = "opacity-50 pointer-events-none";
@endphp

@if($href && !$disabled)
    <a {{ $attributes->merge(['href' => $href, 'class' => "$base {$variants[$variant]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => "$base {$variants[$variant]} " . ($disabled ? $disabledStyle : '')]) }}>
        {{ $slot }}
    </button>
@endif
