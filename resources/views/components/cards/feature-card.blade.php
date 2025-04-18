@props([
    'title' => '',
    'description' => '',
])

<div class="flex flex-col items-center text-center space-y-2 hover:scale-105 transition">
    {{-- ICON (dari slot) --}}
    <div class="rounded-full border-2 p-2 max-w-10">
        {{ $slot }}
    </div>

    {{-- DESC --}}
    <div class="space-y-1">
        <p class="font-medium text-body-lg md:text-heading-sm">{{ $title }}</p>
        <p class="text-label md:text-body-sm">{{ $description }}</p>
    </div>
</div>
