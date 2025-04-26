@props([
    'href' => null,
    'variant' => 'default',
    'type' => 'button',
    'disabled' => false,
    'hoverColor' => 'primary',
    'underlineHover' => false,
    'icon' => false,
])

@php
    $base = 'flex items-center transition';

    if($icon && $variant === 'lg') {
        $base .= ' space-x-1';
    } elseif($icon && $variant === 'default') {
        $base .= ' space-x-0.5';
    }

    $hoverColors = [
    'primary' => 'hover:text-primary/80 dark:hover:text-primary-dark/80',
    'secondary' => 'hover:text-secondary/80 dark:hover:text-secondary-dark/80',
    'tertiary' => 'hover:text-tertiary/80 dark:hover:text-tertiary-dark/80',
    'success' => 'hover:text-success/80 dark:hover:text-success-dark/80',
    'warning' => 'hover:text-warning/80 dark:hover:text-warning-dark/80',    
    'error' => 'hover:text-error/80 dark:hover:text-error-dark/80',
    'on-primary' => 'hover:text-on-primary/80 dark:hover:text-on-primary-dark/80',
    'on-secondary' => 'hover:text-on-secondary/80 dark:hover:text-on-secondary-dark/80',
    'on-tertiary' => 'hover:text-on-tertiary/80 dark:hover:text-on-tertiary-dark/80',
    'on-success' => 'hover:text-on-success/80 dark:hover:text-on-success-dark/80',
    'on-warning' => 'hover:text-on-warning/80 dark:hover:text-on-warning-dark/80',    
    'on-error' => 'hover:text-on-error/80 dark:hover:text-on-error-dark/80',
    ];

    $hover = $hoverColors[$hoverColor] ?? $hoverColors['primary'];
    $hover .= $underlineHover ? ' hover:underline' : '';

    $variants = [
    'default' => "text-label $hover",
    'lg' => "text-body-sm md:text-body-md $hover",
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
