@props(['minWidth' => '0px', 'maxHeight' => '200px', 'right' => false, 'center' => false])

@php
    $base = "absolute text-label bg-surface dark:bg-surface-dark shadow-lg rounded-lg mt-1 z-50 border border-outline-variant dark:border-outline-variant-dark w-full overflow-x-hidden overflow-y-auto dropdown-scroll";
    $position = $right ? 'right-0' : ($center ? 'left-1/2 -translate-x-1/2' : 'left-0');
@endphp

<div x-show="open" @click.away="open = false" class="{{ $base }} {{ $position }}"
    style="min-width: {{ $minWidth }}; max-height: {{ $maxHeight }};"
    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 -translate-y-2">
    {{-- Dropdown items --}}
    {{ $slot }}
</div>