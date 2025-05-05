@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="space-y-2 md:space-y-3 p-2 md:p-3">
        <div class="flex items-center justify-between">
            <div class="space-y-0.5">
                <h2 class="font-bold text-heading-sm">
                    @if ($searchQuery)
                        Hasil Pencarian
                    @else
                        Koleksi Lokapustaka
                    @endif
                </h2>
                <p class="text-label">
                    Menampilkan {{ $books->count() }} dari {{ $books->total() }} buku
                    @if ($searchQuery)
                        hasil pencarian untuk
                        <span class="font-bold">
                            "{{ $searchQuery }}"
                        </span>
                    @endif
                </p>
            </div>
            <form method="get" onchange="submit()" class="text-body-md">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <x-forms.select name="sort" size="sm" :value="request('sort', 'newest')" :options="[
            'newest' => 'Terbaru',
            'az' => 'Judul A-Z',
            'popular' => 'Terpopuler',
            'rating' => 'Rating Tertinggi',
        ]" />
            </form>
        </div>

        {{-- Grid hasil buku --}}
        <div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
            @forelse ($books as $book)
                <x-cards.book :href="route('book.detail', $book->id)" :poster="$book->cover_url" :author="$book->author"
                    :title="$book->title" :description="$book->description"
                    category="{{ $book->category->name }}, {{ $book->genres->pluck('name')->join(', ') }}" />
            @empty
                <p class="text-label">Tidak ada hasil yang ditemukan.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div>
            {{ $books->links() }}
        </div>
    </div>
@endsection