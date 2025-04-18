@props([
    'title' => 'Judul Buku',
    'author' => 'Penulis Buku',
    'category' => 'Kategori Buku',
    'poster' => 'https://placehold.co/150x220?text=Poster',
    'href' => '#',
    'badge' => null,
    'badgeColor' => 'bg-secondary text-on-secondary dark:bg-secondary-dark dark:text-on-secondary-dark',
])

<a href="{{ $href }}"
    aria-label="{{ $author . "'s " . $title }}"
    class="border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark rounded-lg shadow-sm hover:shadow-md bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark overflow-hidden hover:scale-105 transition">
    
    {{-- POSTER --}}
    <div class="relative">
        <img src="{{ $poster }}"
            class="w-full aspect-[2/3] object-cover">
        
        {{-- Badge --}}
        @if ($badge)
            <div class="absolute top-1 left-1 font-medium text-label px-2 py-0.5 rounded-full shadow-sm {{ $badgeColor }}">
                {{ $badge }}
            </div>
        @endif
    </div>
    
    {{-- TEXT --}}
    <div class="px-2 py-1">
        <p class="text-label md:text-body-sm truncate text-on-surface-variant dark:text-on-surface-variant-dark">{{ $author }}</p>
        <h4 class="font-medium text-body-md md:text-body-lg truncate">{{ $title }}</h4>
        <p class="text-label md:text-body-sm truncate text-on-surface-variant dark:text-on-surface-variant-dark">{{ $category }}</p>
    </div>
</a>
