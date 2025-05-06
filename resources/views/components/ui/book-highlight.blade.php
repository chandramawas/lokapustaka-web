@props(['sectionName' => null, 'badges' => [], 'book' => null])

<section id="{{ $sectionName ?? 'section' }}-book-highlight"
    class="size-full relative overflow-hidden bg-primary dark:bg-primary-dark">
    <svg class="absolute opacity-90 size-full" xmlns="http://www.w3.org/2000/svg" version="1.1"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs" preserveAspectRatio="none"
        viewBox="0 0 1440 250">
        <g mask="url(&quot;#SvgjsMask1000&quot;)" fill="none">
            <rect width="1440" height="250" x="0" y="0" fill="#0e2a47"></rect>
            <path d="M37 250L287 0L573.5 0L323.5 250z" fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M248.60000000000002 250L498.6 0L696.6 0L446.6 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M509.20000000000005 250L759.2 0L1014.2 0L764.2 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M715.8000000000001 250L965.8000000000001 0L1214.8000000000002 0L964.8000000000001 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M1416 250L1166 0L1014.5 0L1264.5 250z" fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M1192.4 250L942.4000000000001 0L772.9000000000001 0L1022.9000000000001 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M929.8 250L679.8 0L411.29999999999995 0L661.3 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M682.1999999999999 250L432.19999999999993 0L154.69999999999993 0L404.69999999999993 250z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
            <path d="M1293.254318036348 250L1440 103.25431803634794L1440 250z"
                fill="url(&quot;#SvgjsLinearGradient1001&quot;)"></path>
            <path d="M0 250L146.74568196365206 250L 0 103.25431803634794z"
                fill="url(&quot;#SvgjsLinearGradient1002&quot;)"></path>
        </g>
        <defs>
            <mask id="SvgjsMask1000">
                <rect width="1440" height="250" fill="#ffffff"></rect>
            </mask>
            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%" id="SvgjsLinearGradient1001">
                <stop stop-color="rgba(15, 70, 185, 0.2)" offset="0"></stop>
                <stop stop-opacity="0" stop-color="rgba(15, 70, 185, 0.2)" offset="0.66"></stop>
            </linearGradient>
            <linearGradient x1="100%" y1="100%" x2="0%" y2="0%" id="SvgjsLinearGradient1002">
                <stop stop-color="rgba(15, 70, 185, 0.2)" offset="0"></stop>
                <stop stop-opacity="0" stop-color="rgba(15, 70, 185, 0.2)" offset="0.66"></stop>
            </linearGradient>
        </defs>
    </svg>

    <div
        class="size-full py-4 px-4 md:px-8 flex gap-4 md:gap-8 justify-between backdrop-blur-sm text-on-surface-dark bg-gradient-to-t from-shadow/80 to-transparent">
        {{-- Deskripsi --}}
        <div class="flex flex-col justify-between w-full gap-1">
            <div class="space-y-1 md:space-y-2">
                {{-- Badge --}}
                <div class="flex gap-1">
                    <x-ui.book-badge variant="rating" :rank="$book->rating_summary['average'] ?? '5'"
                        :href="route('book.reviews', $book->isbn ?? '#')" />
                    @foreach ($badges as $badge)
                        <x-ui.book-badge :variant="$badge['variant']" :rank="$badge['rank'] ?? null" :href="$badge['href'] ?? null" />
                    @endforeach
                </div>
                {{-- Deskripsi --}}
                <div class="flex flex-col gap-2">
                    <div class="space-y-0.5">
                        <h3 class="font-medium text-body-sm lg:text-body-lg text-on-surface-variant-dark line-clamp-1">
                            {{ $book->author ?? 'Author' }}
                        </h3>
                        <h2 class="font-bold text-heading-sm md:text-heading-lg line-clamp-1">
                            {{ $book->title ?? 'Title' }}
                        </h2>
                        <p class="text-label text-pretty text-on-surface-variant-dark line-clamp-1 md:line-clamp-2">
                            {{ $book ? $book->genres->pluck('name')->join(', ') : 'Genres' }}
                        </p>
                    </div>
                    <p class="text-label text-pretty text-on-surface-variant-dark line-clamp-2 md:line-clamp-3">
                        {{ $book->description ?? 'Deskripsi tidak tersedia.' }}
                    </p>
                </div>
            </div>
            {{-- Button --}}
            <div class="flex gap-1">
                <x-buttons.button href="#" variant="primary" class="w-full">Baca Sekarang</x-buttons.button>
                <x-buttons.bookmark-toggle :book="$book ?? null" />
                <x-buttons.icon-button :href="route('book.detail', $book->isbn ?? '#')" variant="outline">
                    <x-icons.information />
                </x-buttons.icon-button>
            </div>
        </div>

        {{-- Cover --}}
        <img src="{{ $book->cover_url ?? 'https://placehold.co/150x220?text=Cover+not+available.' }}" loading="lazy"
            class="h-[200px] md:h-[250px] rounded-md object-cover aspect-[2/3]"
            alt="Cover {{ $book->author ?? 'Author' }}'s {{ $book->title ?? 'Title' }}">
    </div>
</section>