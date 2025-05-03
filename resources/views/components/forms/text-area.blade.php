@props(['name', 'placeholder' => '', 'value' => '', 'autofocus' => false, 'size' => 'default', 'label' => null, 'rows' => 3])

@php
    $base = 'ring-1 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark bg-transparent dropdown-scroll transition';

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
        <label for="{{ $name }}" class="font-medium text-on-surface-variant dark:text-on-surface-variant-dark">
            {{ $label }}
        </label>
    @endif

    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $autofocus ? 'autofocus' : '' }} class="{{ $base }} {{ $errorClass }}">{{ old($name, $value) }}</textarea>

    @error($name)
        <x-forms.label>{{ $message }}</x-forms.label>
    @enderror
</div>