@extends('layouts.app')

@section('title', $book->title)

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="space-y-2 md:space-y-3 p-2 md:p-3">
        {{-- Breadcrumbs --}}
        <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $book->category->name, 'url' => route('account.index')],
            ['label' => $book->title, 'url' => route('account.index')],
        ]" />

        {{-- Content --}}
        <div class="grid grid-cols-4 gap-6 p-4 rounded-2xl shadow-lg bg-surface-container dark:bg-surface-container-dark">
            {{-- Poster --}}
            <img src="{{ $book->cover_url }}"
                class="col-span-4 md:col-span-1 border border-outline-variant dark:border-outline-variant-dark object-cover w-full">

            {{-- Book Detail --}}
            <div class="col-span-4 md:col-span-3 space-y-3">
                <x-ui.badge href="#" color="secondary">Trending #1</x-ui.badge>
                {{-- Core --}}
                <div class="space-y-0.5">
                    <h3
                        class="font-medium text-heading-sm text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-2">
                        {{ $book->author }}
                    </h3>
                    <h2 class="font-bold text-display-md line-clamp-2">
                        {{ $book->title }}
                    </h2>
                    <p class="text-label text-on-surface-variant dark:text-on-surface-variant-dark line-clamp-1">
                        {{ $book->category->name }},
                        {{ $book->genres->pluck('name')->join(', ') }}
                    </p>
                </div>
                {{-- CTA BUTTON --}}
                <div class="flex gap-1">
                    <x-buttons.button href="#" variant="primary">Baca Sekarang</x-buttons.button>
                    <x-buttons.button href="#" variant="custom" icon
                        class="shadow-sm hover:shadow-md bg-secondary dark:bg-secondary-dark text-on-secondary dark:text-on-secondary-dark hover:bg-secondary/80 dark:hover:bg-secondary-dark/80 hover:text-on-secondary/80 dark:hover:text-on-secondary-dark/80">
                        <x-icons.star /><span>4.7 (789 ulasan)</span>
                    </x-buttons.button>
                    <x-buttons.icon-button variant="outline"><x-icons.add /></x-buttons.icon-button>
                    <x-buttons.icon-button variant="outline"><x-icons.share /></x-buttons.icon-button>
                </div>
                {{-- Deskripsi Buku --}}
                <div class="space-y-0.5">
                    <div class="flex justify-between items-center">
                        <h4 class="font-bold text-body-xl">Deskripsi</h4>
                        <div x-data="{ open: false }" class="relative">
                            <x-buttons.text-button @click="open = true" underlineHover icon>
                                <span>Baca Selengkapnya</span> <x-icons.chevron-right />
                            </x-buttons.text-button>

                            <x-ui.modal showClose>
                                <div class="space-y-1 text-pretty">
                                    <h4 class="font-bold text-body-xl">Deskripsi Judul Buku</h4>
                                    <p class="text-body-md">
                                        {{ $book->description }}
                                    </p>
                                </div>
                            </x-ui.modal>
                        </div>
                    </div>
                    <p class="text-body-md text-justify line-clamp-3">
                        {{ $book->description }}
                    </p>
                </div>
                {{-- Detail Buku --}}
                <div class="space-y-0.5">
                    <h4 class="font-bold text-body-xl">
                        Detail Buku
                    </h4>
                    <div class="grid grid-cols-2 gap-1 text-body-md">
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
                                ISBN/ISSN
                            </h5>
                            <p class="line-clamp-1">
                                {{ $book->isbn_issn }}
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
            </div>
        </div>

        {{-- Koleksi Terkait --}}
        @php
            $books = [];
            for ($i = 1; $i <= 12; $i++) {
                $books[] = [
                    'author' => 'Penulis ' . $i,
                    'title' => 'Judul Buku ' . $i,
                    'category' => 'Genre',
                ];
            }
        @endphp
        <div class="max-w-6xl mx-auto p-2">
            <x-ui.book-carousel sectionName="other" title="Koleksi Terkait" :books="$books" />
        </div>
    </div>
@endsection