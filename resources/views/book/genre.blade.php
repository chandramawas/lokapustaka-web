@extends('layouts.app')

@section('title', 'Koleksi ' . $genre->name)

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="space-y-2 md:space-y-3 p-2 md:p-3">
        <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => $genre->name, 'url' => route('book.genre.collection', $genre->slug)],
        ]" />

        {{-- Header/page title and sort --}}
        <div class="flex items-center justify-between">
            <div class="space-y-0.5">
                <h2 class="font-bold text-heading-sm">
                    Koleksi {{ $genre->name }}
                </h2>
                <p class="text-label">
                    Menampilkan {{ $books->count() }} dari {{ $books->total() }} buku
                </p>
            </div>
            <form method="get" onchange="submit()" class="text-body-md">
                <x-forms.select name="sort" size="sm" :value="request('sort', 'az')" :options="[
            'az' => 'Judul A-Z',
            'newest' => 'Terbaru',
            'popular' => 'Terpopuler',
            'rating' => 'Rating Tertinggi',
        ]" />
            </form>
        </div>

        {{-- Grid hasil buku --}}
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-1">
            @forelse ($books as $book)
                <x-cards.book :book="$book" />
            @empty
                <p class="text-label">Tidak ada hasil yang ditemukan.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($books->lastpage() > 1)
            <div class="flex items-center text-label">
                {{-- Tombol Previous --}}
                <div class="w-1/3 flex justify-start">
                    @if (!$books->onFirstPage())
                        <a href="{{ $books->previousPageUrl() }}"
                            class="w-fit flex items-center justify-center rounded-md text-center space-x-0.5 transition px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark">
                            <x-icons.chevron-left />
                            <span class="hidden md:block">Sebelumnya</span>
                        </a>
                    @endif
                </div>

                {{-- Info Halaman di Tengah --}}
                <div class="w-1/3 flex justify-center">
                    <div
                        class="w-fit rounded-md text-center px-3 py-1 bg-surface-dim dark:bg-surface-dim-dark text-on-surface dark:text-on-surface-dark">
                        <span class="hidden md:inline-block">Halaman</span>
                        {{ $books->currentPage() }} / {{ $books->lastPage() }}
                    </div>
                </div>

                {{-- Tombol Next --}}
                <div class="w-1/3 flex justify-end">
                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}"
                            class="w-fit flex items-center justify-center rounded-md text-center space-x-0.5 transition px-3 py-1 text-label border border-outline-variant dark:border-outline-variant-dark hover:border-outline dark:hover:border-outline-dark">
                            <span class="hidden md:block">Berikutnya</span>
                            <x-icons.chevron-right />
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection