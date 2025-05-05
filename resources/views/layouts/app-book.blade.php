{{--
|------------------------------------------------------------------------------------------------------------------
| Layout ini adalah template khusus untuk halaman-halaman buku di Lokapustaka.
| Layout ini mewarisi struktur dari layouts.app, jadi beberapa bagian dari app.blade.php tetap tersedia.
|-------------------------------------------------------------------------------------------------------------------
| Cara Pakai:
| @extends('layouts.app-book')
|------------------------------------------------------------------------------------------------------------------
| Bagian dari app.blade.php yang masih bisa digunakan:
| 1. @section('title')
| - Untuk mengatur title tab browser.
| - Default: 'Baca Buku Digital Tanpa Batas'.
|
| Bagian spesifik app-book yang bisa diisi:
| 2. @section('breadcrumbs')
| - Breadcrumb navigasi untuk menunjukkan lokasi halaman saat ini.
| - Opsional.
|
| 3. @section('first-content')
| - Kontainer untuk konten di sisi kiri (contoh: informasi buku, filter, dll.).
| - Wajib diisi.
|
| 4. @section('second-content')
| - Kontainer untuk konten di sisi kanan (contoh: daftar buku, detail buku, dll.).
| - Wajib diisi.
|
| Fitur tambahan:
| - Carousel buku terkait di bagian bawah halaman.
| - Desain responsif dengan grid layout.
|------------------------------------------------------------------------------------------------------------------}}

@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="space-y-2 md:space-y-3 p-2 md:p-3">
        {{-- Breadcrumbs --}}
        @yield('breadcrumbs')

        {{-- Content --}}
        <div
            class="grid grid-cols-4 gap-3 md:gap-6 p-4 rounded-2xl shadow-lg bg-surface-container dark:bg-surface-container-dark">
            {{-- Left --}}
            <div class="col-span-4 md:col-span-1 space-y-3">
                @yield('first-content')
            </div>

            {{-- Right --}}
            <div class="col-span-4 md:col-span-3 space-y-3">
                @yield('second-content')
            </div>
        </div>

        {{-- Koleksi Terkait --}}
        @php
            $books = [];
            for ($i = 1; $i <= 12; $i++) {
                $books[] = [
                    'author' => 'Penulis ' . $i,
                    'title' => 'Judul Buku ' . $i,
                    'genre' => 'Genre',
                ];
            }
        @endphp
        <div class="max-w-6xl mx-auto p-2">
            <x-ui.book-carousel sectionName="other" title="Koleksi Terkait" :books="$books" />
        </div>
    </div>
@endsection