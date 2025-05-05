@props(['variant' => 'new', 'rank' => null, 'href' => null])

@php
    $variants = [
     'new' => [
        'color' => 'bg-primary dark:bg-primary-dark text-on-primary dark:text-on-primary-dark',
        'text' => 'Baru'
],
     'trending' => [
        'color' => 'bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark',
        'text' => 'Trending'
],
     'recommend' => [
        'color' => 'bg-tertiary dark:bg-tertiary-dark text-on-tertiary dark:text-on-tertiary-dark',
        'text' => 'Rekomendasi Tim Loka'
],
     'rating' => [
        'color' => 'bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark',
        'text' => 'Rating Tinggi'
]
    ]
@endphp

<a @if ($href) href="{{ $href }}" @endif class="font-medium text-label w-fit px-2 py-0.5 rounded-full animate-pulse {{ $variants[$variant]['color'] }}">
    {{ $variants[$variant]['text'] }}
    @if ($rank)
        #{{ $rank }}
    @endif
</a>