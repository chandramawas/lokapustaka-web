@extends('layouts.app-book')

@section('title', $book->title)

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $book->mainGenre->name, 'url' => route('book.genre.collection', $book->mainGenre->slug)],
            ['label' => $book->title, 'url' => route('book.detail', $book->slug)],
        ]" />
@endsection

@section('first-content')
    <img src="{{ $book->cover_url ?? 'https://placehold.co/150x220?text=Cover+not+available.' }}"
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
        {{-- Read Buku --}}
        <x-buttons.read-button :book="$book" />

        {{-- Review Buku --}}
        <x-buttons.rating-button :book="$book" />

        {{-- Bookmark Buku --}}
        <livewire:bookmark-toggle :book="$book" />

        {{-- Share Buku --}}
        <x-buttons.share-button :url="route('book.detail', $book->slug ?? '#')" :title="$book && $book->title ? $book->title . ' - Lokapustaka' : 'Lokapustaka'" />
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
                        <h4 class="font-bold text-body-md md:text-body-lg lg:text-body-xl">Deskripsi</h4>
                        <p
                            class="text-body-sm md:text-body-md text-on-surface-variant dark:text-on-surface-variant-dark text-justify break-words">
                            {{ $book->description ?? 'Deskripsi belum tersedia.' }}
                        </p>
                    </div>
                </x-ui.modal>
            </div>
        </div>
        <p class="text-body-sm md:text-body-md text-justify line-clamp-3">
            {{ $book->description ?? 'Deskripsi belum tersedia.' }}
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
                    {{ $book->publisher ?? '-' }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Tahun Terbit
                </h5>
                <p class="line-clamp-1">
                    {{ $book->year ?? '-' }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    ISBN
                </h5>
                <p class="line-clamp-1">
                    {{ $book->isbn ?? '-' }}
                </p>
            </div>
            <div>
                <h5 class="font-medium text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                    Halaman
                </h5>
                <p class="line-clamp-1">
                    {{ $book->pages ?? '-' }}
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

@section('book-carousel')
    <x-ui.book-carousel sectionName="related" title="Koleksi Terkait" :books="$relatedBooks" />
@endsection