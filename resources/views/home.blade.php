@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    {{-- Highlight --}}
    <section id="highlight" class="swiper highlightSwiper w-full">
        <div class="swiper-wrapper">

            {{-- Trending --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="highest-rating-book" :badges="[['color' => 'secondary', 'content' => 'Trending #1']]" />
            </div>

            {{-- Baru Rilis --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="highest-rating-book" :badges="[['color' => 'primary', 'content' => 'Baru']]" />
            </div>

            {{-- Rating Tertinggi --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="highest-rating-book" :badges="[['color' => 'secondary', 'content' => 'Rating #1']]" />
            </div>

            {{-- Rekomendasi Tim Loka --}}
            <div class="swiper-slide">
                <x-ui.book-highlight sectionName="recommendation-book" :badges="[['color' => 'tertiary', 'content' => 'Rekomendasi Tim Loka']]" :id="$recommendationBook->id" :author="$recommendationBook->author"
                    :title="$recommendationBook->title"
                    description="{{ $recommendationBook->description ?? 'Deskripsi tidak tersedia.' }}"
                    poster="{{ $recommendationBook->cover_url ?? 'https://placehold.co/150x220?text=Poster+not+available.' }}" />
            </div>

        </div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </section>

    {{-- Chip Kategori --}}
    <section id="categoryChip" class="p-3 lg:px-6 grid grid-cols-2 md:grid-cols-4 gap-1 md:gap-2">
        <x-buttons.button href="#" variant="secondary-lg" class="hover:scale-105">Novel</x-buttons.button>
        <x-buttons.button href="#" variant="secondary-lg" class="hover:scale-105">Non-Fiksi</x-buttons.button>
        <x-buttons.button href="#" variant="secondary-lg" class="hover:scale-105">Pendidikan</x-buttons.button>
        <x-buttons.button href="#" variant="secondary-lg" class="hover:scale-105">Teknologi & Sains</x-buttons.button>
    </section>

    {{-- Section Buku --}}
    <div class="max-w-6xl mx-auto mt-2 mb-6 px-4 space-y-4">
        @php
            $books = [];
            for ($i = 1; $i <= 7; $i++) {
                $books[] = [
                    'author' => 'Penulis ' . $i,
                    'title' => 'Judul Buku ' . $i,
                    'category' => 'Genre',
                ];
            }
        @endphp

        <x-ui.book-carousel sectionName="reading" title="Riwayat Baca" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="saved" title="Disimpan" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="history" title="Sejarah" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="fantasy" title="Fantasi" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="science-fiction" title="Fiksi Ilmiah" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="technology" title="Teknologi" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="science" title="Sains" href="#" :books="$books" />
        <x-ui.book-carousel sectionName="thriller" title="Thriller" href="#" :books="$books" />
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.highlightSwiper', {
                slidesPerView: 1,
                centeredSlides: true,
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