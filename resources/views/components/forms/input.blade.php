@props([
    'type' => 'text',
    'name',
    'placeholder' => '',
    'value' => '',
    'leadingIcon' => false,
    'autofocus' => false,
    'size' => 'default',
    'label' => null,
])

@php
$base = 'ring-1 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark bg-transparent transition';

if ($size === 'default') {
    $base .= ' rounded-lg p-2';
} elseif ($size === 'sm') {
    $base .= ' rounded-md px-2 py-1';
}

$errorClass = $errors->has($name) 
    ? 'ring-error dark:ring-error-dark' 
    : 'ring-outline-variant dark:ring-outline-variant-dark';
@endphp

<div class="flex flex-col space-y-0.5">
    @if ($label)
    <label for="{{ $name }}" class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">{{ $label }}</label>
    @endif
    <div class="relative flex flex-col">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ old($name, $value) }}" {{ $autofocus ? 'autofocus' : '' }}
    class="{{ $base }} {{ $errorClass }}
        @if ($type === 'password') pr-6 @endif
        @if ($leadingIcon) pl-6 @endif" />

        {{-- LEADING ICON --}}
        @if ($leadingIcon)
            <span class="absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none">
                {{-- ICON --}}
                {{ $slot }}
            </span>
        @endif

        {{-- ICON TOGGLE PASSWORD --}}
        @if ($type === 'password')
            <button type="button" aria-label="Toggle Password Visibility"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-on-surface dark:text-on-surface-dark"
                onclick="togglePasswordVisibility('{{ $name }}')">

                <span id="show-{{ $name }}" class="">
                    <x-icons.visibility class="h-3" variant="show" />
                </span>

                <span id="hide-{{ $name }}" class="hidden">
                    <x-icons.visibility class="h-3" variant="hide" />
                </span>

            </button>
        @endif

    </div>
    {{-- ERROR MESSAGE --}}
    @error($name)
        <x-forms.label>{{ $message }}</x-label>
    @enderror
</div>