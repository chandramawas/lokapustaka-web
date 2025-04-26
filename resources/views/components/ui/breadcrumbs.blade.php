@props(['items' => []])

<nav class="text-label" aria-label="Breadcrumbs">
    <ol class="flex items-center space-x-1">
        @foreach ($items as $index => $item)
            <li class="flex items-center space-x-1">
                @if ($index > 0)
                    {{-- Icon separator --}}
                    <x-icons.chevron-right />
                @endif

                @if ($loop->last)
                    <span>{{ $item['label'] }}</span>
                @else
                    <x-buttons.text-button :href="$item['url']" underlineHover>
                        {{ $item['label'] }}
                    </x-buttons.text-button>
                @endif
            </li>
        @endforeach
    </ol>
</nav>