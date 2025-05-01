@props([
'href' => '#',
'poster' => 'https://placehold.co/150x220?text=Poster+not+available.',
'badge' => null,
'author' => 'Penulis',
'title' => 'Judul',
'category' => 'Kategori',
'description' => 'Deskripsi belum tersedia.',
])

<a href="{{ $href }}" aria-label="{{ $author }}'s {{ $title }}"
    class="group relative block w-full h-full rounded-lg overflow-hidden transition border border-outline-variant">

    {{-- POSTER --}}
    <div class="relative transition">
        <img src="{{ $poster }}" alt="Poster {{ $author }}'s {{ $title }}" loading="lazy"
            class="w-full aspect-[2/3] object-cover">

        @if ($badge)
            <div
                class="absolute top-1 right-1">
                <x-ui.badge color="{{ $badge['color'] }}">{{ $badge['content'] }}</x-ui.badge>
            </div>
        @endif
    </div>

    {{-- Overlay --}}
    <div
        class="absolute inset-0 flex flex-col justify-between p-2 bg-gradient-to-t from-surface-dark to-transparent opacity-0 group-hover:backdrop-brightness-75 group-hover:backdrop-blur-sm group-hover:opacity-100 transition duration-300">
        <div>
            {{-- Penulis --}}
            <h5 class="text-label truncate text-on-surface-variant-dark">
                {{ $author }}
            </h5>
            {{-- Judul --}}
            <h4 class="font-medium text-body-md line-clamp-3 text-on-surface-dark">
                {{ $title }}
            </h4>
            {{-- Kategori --}}
            <p class="text-label truncate text-on-surface-variant-dark">
                {{ $category }}
            </p>
        </div>
        {{-- Deskripsi --}}
        <p class="text-label line-clamp-3 text-on-surface-variant-dark">
            {{ $description }}
        </p>
    </div>
</a>