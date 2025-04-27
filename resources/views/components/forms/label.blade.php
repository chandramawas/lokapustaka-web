@props(['variant' => 'error', 'textAlign' => 'right'])

@php
    $base = 'text-label';

    $aligns = [
        'left' => 'text-left',
        'right' => 'text-right',
        'center' => 'text-center',
    ];

    $variants = [
        'error' => 'text-error dark:text-error-dark',
        'success' => 'text-success dark:text-success-dark',
        'warning' => 'text-warning dark:text-warning-dark',
        'inherit' => '',
    ]
@endphp

<p {{ $attributes->class([$base, $variants[$variant], $aligns[$textAlign]]) }}>
    {{ $slot }}
</p>