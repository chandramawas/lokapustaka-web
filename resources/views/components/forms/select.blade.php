@props(['name', 'options' => [], 'placeholder' => '', 'value' => '', 'leadingIcon' => false, 'autofocus' => false, 'size' => 'default', 'label' => null,])

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
        <label for="{{ $name }}"
            class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">{{ $label }}</label>
    @endif
    <div class="relative flex flex-col">
        <select name="{{ $name }}" id="{{ $name }}" {{ $autofocus ? 'autofocus' : '' }}
            class="{{ $base }} {{ $errorClass }} {{ $leadingIcon ? 'pl-6' : '' }}">
            @if ($placeholder)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif
            @foreach ($options as $key => $option)
                <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>

        {{-- LEADING ICON --}}
        @if ($leadingIcon)
            <span class="absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none">
                {{-- ICON --}}
                {{ $slot }}
            </span>
        @endif
    </div>

    {{-- ERROR MESSAGE --}}
    @error($name)
        <x-forms.label>{{ $message }}</x-forms.label>
    @enderror
</div>