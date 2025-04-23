@props([
    'variant' => 'iconButton',
    'iconButton' => 'secondary',
    'textButton' => 'default',
])

@if ($variant === 'text')
    <x-buttons.text-button id="theme-toggle" variant="{{ $textButton }}" aria-label="Ganti Tema Website">
        {{-- Light Mode --}}
        <span id="icon-light" class="hidden">
            <span class="flex space-x-0.5">
                <x-icons.theme variant="light" />
                <span>Light</span>
            </span>
        </span>
        {{-- Dark Mode --}}
        <span id="icon-dark" class="hidden">
            <span class="flex space-x-0.5">
                <x-icons.theme variant="dark" />
                <span>Dark</span>
            </span>
        </span>
    </x-buttons.text-button>
@else
    <x-buttons.icon-button id="theme-toggle" variant="{{ $iconButton }}" aria-label="Ganti Tema Website">
        {{-- Light Mode Icon --}}
        <span id="icon-light" class="hidden">
            <x-icons.theme variant="light" />
        </span>
        {{-- Dark Mode Icon --}}
        <span id="icon-dark" class="hidden">
            <x-icons.theme variant="dark" />
        </span>
    </x-buttons.icon-button>
@endif