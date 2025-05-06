@extends('layouts.app-bookshelf')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Koleksi Saya', 'url' => route('bookshelf.index')],
        ]" />
@endsection

@section('bookshelf-content')
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-1 md:gap-2">
        @forelse ($books as $book)
            <x-cards.book :book="$book" />
        @empty
            <div class="col-span-full text-center text-label text-on-surface-variant dark:text-on-surface-variant-dark">
                Belum ada buku yang disimpan.
            </div>
        @endforelse
    </div>
@endsection