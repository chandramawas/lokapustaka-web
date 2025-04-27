@props([
    'title' => null,
    'items' => [],
    'activeColor' => 'primary',
])
@php
    $base = 'flex items-center space-x-1 p-1 md:px-2 md:py-1 rounded-lg text-body-sm';

    $activeColors = [
        'primary' => 'bg-primary dark bg-primary-dark text-on-primary dark:text-on-primary-dark',
        'secondary' => 'bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark',
        'tertiary' => 'bg-tertiary dark:bg-tertiary-dark text-on-tertiary dark:text-on-tertiary-dark',
        'error' => 'bg-error dark:bg-error-dark text-on-error dark:text-on-error-dark',
        'warning' => 'bg-warning dark:bg-warning-dark text-on-warning dark:text-on-warning-dark',
        'success' => 'bg-success dark:bg-success-dark text-on-success dark:text-on-success-dark',
        'surface' => 'bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark',
        'inverse-surface' => 'bg-inverse-surface dark:bg-inverse-surface-dark text-on-inverse-surface dark:text-on-inverse-surface-dark',
    ];

    $hoverColors = [
        'primary' => 'hover:bg-primary/10 dark:hover:bg-primary-dark/10',
        'secondary' => 'hover:bg-secondary/10 dark:hover:bg-secondary-dark/10',
        'tertiary' => 'hover:bg-tertiary/10 dark:hover:bg-tertiary-dark/10',
        'error' => 'hover:bg-error/10 dark:hover:bg-error-dark/10',
        'warning' => 'hover:bg-warning/10 dark:hover:bg-warning-dark/10',
        'success' => 'hover:bg-success/10 dark:hover:bg-success-dark/10',
        'surface' => 'hover:bg-surface/10 dark:hover:bg-surface-dark/10',
        'inverse-surface' => 'hover:bg-inverse-surface/10 dark:hover:bg-inverse-surface-dark/10',
    ];

    // State
    $active = "{$activeColors[$activeColor]} font-medium";
    $idle = "{$hoverColors[$activeColor]} font-normal";
@endphp
<nav class="space-y-2">
        <div class="md:space-y-1">
            @if ($title)
                <h2 class="font-bold text-body-md hidden md:flex">{{ $title }}</h2>
            @endif
    <ul class="space-y-1">
    @foreach ($items as $item)
        <li>
            <a href="{{ route($item['url']) }}"
                class="{{ $base }} {{ request()->routeIs($item['url']) ? $active : $idle }}">
                            <x-dynamic-component :component="$item['icon']" height="20px" />
                        <span class="hidden md:flex">{{ $item['label'] }}</span>
                    </a>
                </li>
    @endforeach
        </ul>
    </div>
</nav>