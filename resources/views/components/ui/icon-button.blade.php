@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center rounded-full transition';

    $variants = [
        'primary' => "p-1 border border-outline-variant dark:border-outline-variant-dark hover:border-outline bg-primary text-on-primary hover:bg-primary/80 hover:text-on-primary/80",
        'primary-lg' => "p-2 border border-outline-variant dark:border-outline-variant-dark hover:border-outline bg-primary text-on-primary hover:bg-primary/80 hover:text-on-primary/80",
        'secondary' => "p-1 border border-outline-variant dark:border-outline-variant-dark hover:border-outline bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark hover:bg-surface/80 dark:hover:bg-surface-dark/80 hover:text-on-surface/80 dark:hover:text-on-surface-dark/80",
        'secondary-lg' => "p-2 border border-outline-variant dark:border-outline-variant-dark hover:border-outline bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark hover:bg-surface/80 dark:hover:bg-surface-dark/80 hover:text-on-surface/80 dark:hover:text-on-surface-dark/80",
        'outline' => "p-1 border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark",
        'outline-lg' => "p-2 border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark",
        'text' => "p-1 hover:text-primary/80 dark:hover:text-primary-dark/80",
        'text-lg' => "p-2 hover:text-primary/80 dark:hover:text-primary-dark/80",
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
