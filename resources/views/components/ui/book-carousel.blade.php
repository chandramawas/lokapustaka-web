@props(['books' => [], 'href' => null, 'sectionName', 'title' => null])

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
    @if ($books)
        {{-- Swiper container --}}
        <div class="swiper bookSwiper" id="{{ $swiperId }}">
            <div class="swiper-wrapper">
                @foreach ($books as $book)
                    <div class="swiper-slide max-w-[150px] md:max-w-[175px] lg:max-w-[190px]">
                        <x-cards.book :book="$book" />
                    </div>
                @endforeach
            </div>

            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    @else
        <p class="text-label">Tidak ada koleksi.</p>
    @endif

</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('#{{ $swiperId }}', {
                slidesPerView: 'auto',
                spaceBetween: 8,
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
                }
            });

            function updateNavButtons(swiper) {
                const prev = swiper.navigation.prevEl;
                const next = swiper.navigation.nextEl;

                // Atur display-nya langsung
                prev.style.display = swiper.isBeginning ? 'none' : 'flex';
                if (swiper.isLocked) {
                    next.style.display = 'none';
                } else if (swiper.isEnd) {
                    next.style.display = 'none';
                } else {
                    next.style.display = 'flex';
                }
            }
        });
    </script>
@endpush