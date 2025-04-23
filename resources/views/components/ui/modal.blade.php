@props(['showClose' => false])

<div x-show="open" x-cloak @click.away="open = false"
    class="fixed inset-0 flex items-center justify-center z-50 backdrop-brightness-50">
    {{-- Background overlay --}}
    <div class="absolute inset-0" @click="open = false"></div>

    {{-- Pop-up box --}}
    <div
        class="relative bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark p-4 rounded-lg shadow-sm w-[90%] max-w-md">
        {{ $slot }}

        @if ($showClose)
            <button @click="open = false" class="absolute top-2 right-2">
                &times;
            </button>
        @endif
    </div>
</div>