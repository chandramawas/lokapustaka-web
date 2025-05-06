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
        <div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
            @forelse ($books as $book)
                <x-cards.book :isbn="$book->isbn" :poster="$book->cover_url" :author="$book->author" :title="$book->title"
                    :description="$book->description" :genre="$book->genres->pluck('name')->join(', ')" />
            @empty
                <p class="text-label">Tidak ada hasil yang ditemukan.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="text-body-md">
            {{ $books->links() }}
        </div>
    </div>
@endsection