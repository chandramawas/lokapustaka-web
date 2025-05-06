@extends('layouts.app-book')

@section('title', $book->title)

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $book->mainGenre->name, 'url' => route('book.genre.collection', $book->mainGenre->slug)],
            ['label' => $book->title, 'url' => route('book.detail', $book->isbn)],
        ]" />
@endsection

@section('first-content')
    <img src="{{ $book->cover_url ?? 'https://placehold.co/150x220?text=Poster+not+available.' }}"
        class="border border-outline-variant dark:border-outline-variant-dark object-cover aspect-[2/3] w-full">
@endsection

@section('second-content')
    <x-ui.book-badge variant="trending" rank="1" href="#" />
    {{-- Core --}}
    <div class="space-y-0.5">
        <h3
            class="font-medium text-body-lg md:text-body-xl lg:text-heading-sm text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-2">
            {{ $book->author }}
        </h3>
        <h2 class="font-bold text-heading-lg md:text-heading-xl lg:text-display-md line-clamp-2">
            {{ $book->title }}
        </h2>
        <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-1">
            {{ $book->genres->pluck('name')->join(', ') }}
        </p>
    </div>
    {{-- CTA BUTTON --}}
    <div class="flex gap-1">
        <x-buttons.button href="#" variant="primary">Baca Sekarang</x-buttons.button>
        <x-buttons.button :href="route('book.reviews', $book->isbn)" variant="custom" icon
            class="shadow-sm hover:shadow-md bg-secondary-container dark:bg-secondary-container-dark text-on-secondary-container dark:text-on-secondary-container-dark hover:bg-secondary-container/80 dark:hover:bg-secondary-container-dark/80 hover:text-on-secondary-container/80 dark:hover:text-on-secondary-container-dark/80">
            <x-icons.star />
            <span>
                {{ $book->rating_summary['average'] . ' (' . $book->rating_summary['count'] . ' Ulasan)' ?? 'Belum ada ulasan' }}
            </span>
        </x-buttons.button>
        <x-buttons.icon-button variant="outline"><x-icons.add /></x-buttons.icon-button>
        <x-buttons.icon-button variant="outline"><x-icons.share /></x-buttons.icon-button>
    </div>
    {{-- Deskripsi Buku --}}
    <div class="space-y-0.5">
        <div class="flex justify-between items-center">
            <h4 class="font-bold text-body-md md:text-body-lg lg:text-body-xl">Deskripsi</h4>
            <div x-data="{ open: false }" class="relative">
                <x-buttons.text-button @click="open = true" underlineHover icon>
                    <span>Baca Selengkapnya</span> <x-icons.chevron-right />
                </x-buttons.text-button>

                <x-ui.modal showClose>
                    <div class="space-y-1 text-pretty">
                        <h4 class="font-bold text-body-md md:text-body-lg lg:text-body-xl">Deskripsi Judul Buku</h4>
                        <p class="text-body-sm md:text-body-md">
                            {{ $book->description }}
                        </p>
                    </div>
                </x-ui.modal>
            </div>
        </div>
        <p class="text-body-sm md:text-body-md text-justify line-clamp-3">
            {{ $book->description }}
        </p>
    </div>
    {{-- Detail Buku --}}
    <div class="space-y-0.5">
        <h4 class="font-bold text-body-md md:text-body-lg lg:text-body-xl">
            Detail Buku
        </h4>
        <div class="grid grid-cols-2 gap-1 text-body-sm md:text-body-md">
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Penerbit
                </h5>
                <p class="line-clamp-1">
                    {{ $book->publisher }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Tahun Terbit
                </h5>
                <p class="line-clamp-1">
                    {{ $book->year }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    ISBN
                </h5>
                <p class="line-clamp-1">
                    {{ $book->isbn }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Halaman
                </h5>
                <p class="line-clamp-1">
                    {{ $book->pages }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Bahasa
                </h5>
                <p class="line-clamp-1">
                    {{ $book->language }}
                </p>
            </div>
        </div>
    </div>
@endsection