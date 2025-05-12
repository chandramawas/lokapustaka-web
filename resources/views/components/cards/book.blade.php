@props(['book' => null, 'badge' => null])

<a href="{{ route('book.detail', $book->slug ?? '#') }}"
    aria-label="{{ $book->author ?? 'Author' }}'s {{ $book->title ?? 'Title' }}"
    class="group relative block w-full h-full rounded-lg overflow-hidden transition border border-outline-variant dark:border-outline-variant-dark">

    {{-- POSTER --}}
    <div class="relative transition">
        <img src="{{ $book->cover_url ?? 'https://placehold.co/150x220?text=Cover+not+available.' }}"
            alt="Cover {{ $book->author ?? 'Author' }}'s {{ $book->title ?? 'Title' }}" loading="lazy"
            class="w-full aspect-[2/3] object-cover">

        @if ($badge)
            <div class="absolute top-1 right-1">
                <x-ui.book-badge :variant="$badge['variant']" :rank="$badge['rank'] ?? null" :href="$badge['href'] ?? null" />
            </div>
        @endif

        @if ($book->progress)
            <div class="absolute z-30 bottom-0 inset-x-0 h-0.5 bg-surface dark:bg-surface-dark">
                <div id="reading-progress-bar" style="width: {{ $book->progress->progress_percent }}%;"
                    class="size-full transition-all duration-500 {{ $book->progress->progress_percent < 50 ? 'bg-secondary-container' : 'bg-primary-container' }}">
                </div>
            </div>
        @endif
    </div>

    {{-- Overlay --}}
    <div
        class="absolute inset-0 flex flex-col justify-between p-2 bg-gradient-to-t from-surface-dark to-transparent opacity-0 group-hover:backdrop-brightness-75 group-hover:backdrop-blur-sm group-hover:opacity-100 transition duration-300">
        <div>
            {{-- Penulis --}}
            <h5 class="text-label line-clamp-1 text-on-surface-variant-dark">
                {{ $book->author ?? 'Author' }}
            </h5>
            {{-- Judul --}}
            <h4 class="font-medium text-body-sm md:text-body-md line-clamp-3 text-on-surface-dark">
                {{ $book->title ?? 'Title' }}
            </h4>
            {{-- Genre --}}
            <p class="text-label line-clamp-1 md:line-clamp-2 text-on-surface-variant-dark">
                {{ $book && $book->genres ? $book->genres->pluck('name')->join(', ') : 'Genres' }}
            </p>
        </div>
        {{-- Deskripsi --}}
        <p class="text-label line-clamp-3 text-on-surface-variant-dark">
            {{ $book->description ?? 'Deskripsi belum tersedia.' }}
        </p>
    </div>
</a>