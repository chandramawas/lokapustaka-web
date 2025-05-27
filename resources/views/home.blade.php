@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    {{-- Highlight --}}
    <section id="highlight" class="swiper highlightSwiper w-full">
        <div class="swiper-wrapper">

            {{-- Trending --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="trending" :badges="[['variant' => 'trending', 'rank' => '1', 'href' => '#']]"
                    :book="$books['highlights']['trending']" />
            </div>

            {{-- Baru Rilis --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="newest" :badges="[['variant' => 'new', 'href' => '#']]"
                    :book="$books['highlights']['newest']" />
            </div>

            {{-- Rekomendasi Tim Loka --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="recommendation" :badges="[['variant' => 'recommend']]"
                    :book="$books['highlights']['recommendation']" />
            </div>

        </div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </section>

    {{-- Chip Genre --}}
    @if ($topGenres)
        <section id="genreChip" class="p-3 lg:px-6 grid grid-cols-2 md:grid-cols-4 gap-1 md:gap-2">
            @foreach ($topGenres as $genre)
                <x-buttons.button :href="route('book.genre.collection', $genre->slug)" variant="secondary-lg"
                    class="hover:scale-105">{{ $genre->name }}</x-buttons.button>
            @endforeach
        </section>
    @endif

    {{-- Section Buku --}}
    <div class="max-w-6xl mx-auto w-full mt-2 mb-6 px-4 space-y-4">
        @if($books['notCompleted']->isNotEmpty())
            <x-ui.book-carousel sectionName="reading" title="Lanjutkan Baca" :href="route('bookshelf.history')"
                :books="$books['notCompleted']" />
        @endif
        @if($books['saved']->isNotEmpty())
            <x-ui.book-carousel sectionName="saved" title="Disimpan" :href="route('bookshelf.index')"
                :books="$books['saved']" />
        @endif
        @foreach ($booksByGenre as $genre)
            @if($genre->books->isNotEmpty())
                <x-ui.book-carousel sectionName="{{ $genre->slug }}" :title="$genre->name" :href="route('book.genre.collection', $genre->slug)" :books="$genre->books" />
            @endif
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const highlightSwiper = new Swiper('.highlightSwiper', {
                slidesPerView: 1,
                centeredSlides: true,
                autoHeight: true,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 4000,
                    pauseOnMouseEnter: true,
                },
            });
        });
    </script>
@endpush