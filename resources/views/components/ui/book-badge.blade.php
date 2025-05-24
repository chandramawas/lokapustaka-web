@props(['variant' => 'new', 'rank' => null])

@php
    $variants = [
        'new' => [
            'color' => 'bg-primary dark:bg-primary-dark text-on-primary dark:text-on-primary-dark',
            'text' => 'Baru Ditambahkan',
        ],
        'trending' => [
            'color' => 'bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark',
            'text' => 'Trending',
        ],
        'recommend' => [
            'color' => 'bg-tertiary dark:bg-tertiary-dark text-on-tertiary dark:text-on-tertiary-dark',
            'text' => 'Rekomendasi Tim Loka',
        ],
        'rating' => [
            'color' => 'bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark',
        ],
    ]
@endphp

<a class="font-medium text-label w-fit px-2 py-0.5 rounded-full animate-pulse {{ $variants[$variant]['color'] }}">
    @if ($variant === 'rating')
        <div class="flex gap-0.5 font-bold">
            <x-icons.star/>
            <span>
                {{ $rank }}
            </span>
        </div>
    @else
        {{ $variants[$variant]['text'] }}
        @if ($rank)
            #{{ $rank }}
        @endif
    @endif
</a>
