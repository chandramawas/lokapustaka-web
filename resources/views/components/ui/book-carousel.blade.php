@props(['books', 'href' => null, 'sectionName', 'title' => null])

@php
    $swiperId = 'bookSwiper-' . $sectionName;
@endphp

<section id="{{ $sectionName }}-books" class="space-y-1 md:space-y-2">
    {{-- Title --}}
    @if ($title)
        <div class="flex justify-between items-end px-1">
            <h2 class="font-bold text-heading-sm md:text-heading-md">{{ $title }}</h2>
            @if ($href)
                <x-buttons.text-button href="{{ $href }}" underlineHover>Lihat Semua</x-buttons.text-button>
            @endif
        </div>
    @endif
    {{-- Swiper container --}}
    <div class="swiper bookSwiper" id="{{ $swiperId }}">
        <div class="swiper-wrapper">
            @foreach ($books as $book)
                <div class="swiper-slide max-w-[125px] md:max-w-[150px]">
                    <x-cards.book href="{{ $book['href'] ?? '#' }}"
                        poster="{{ $book['poster'] ?? 'https://placehold.co/150x220?text=Poster+not+available.' }}"
                        :badge="$book['badge'] ?? null" author="{{ $book['author'] }}" title="{{ $book['title'] }}"
                        genre="{{ $book['genre'] }}"
                        description="{{ $book['description'] ?? 'Deskripsi belum tersedia.' }}" />
                </div>
            @endforeach
        </div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('#{{ $swiperId }}', {
                slidesPerView: 'auto',
                spaceBetween: 16,
                centeredSlides: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                on: {
                    init: function () {
                        updateNavButtons(this);
                    },
                    slideChange: function () {
                        updateNavButtons(this);
                    },
                    reachEnd: function () {
                        updateNavButtons(this);
                    },
                    reachBeginning: function () {
                        updateNavButtons(this);
                    }
                }
            });

            function updateNavButtons(swiper) {
                const prev = swiper.navigation.prevEl;
                const next = swiper.navigation.nextEl;

                // Atur display-nya langsung
                prev.style.display = swiper.isBeginning ? 'none' : 'flex';
                next.style.display = swiper.isEnd ? 'none' : 'flex';
            }
        });
    </script>
@endpush