{{--
|-------------------------------------------------------------------------------------
| Layout: Bookshelf Layout (layouts.bookshelf)
|-------------------------------------------------------------------------------------
| Deskripsi:
| Template dasar untuk halaman "Rak Buku" pengguna di Lokapustaka. Layout ini
| digunakan untuk menampilkan koleksi buku seperti "Disimpan", "Lanjut Baca", dan "Ulasan".
| Tampilan dan struktur layout ini konsisten dengan halaman akun.
|-------------------------------------------------------------------------------------
| Cara Pakai:
| @extends('layouts.bookshelf')
|
| Bagian yang bisa diisi:
|
| 1. @section('title', 'Judul Halaman')
| - Menentukan judul tab di browser.
| - Default: 'Koleksi Saya'
|
| 2. @section('navbar', view('layouts.navbar'))
| - (Opsional) Menentukan navbar yang digunakan.
| - Jika tidak diisi, navbar default akan digunakan.
|
| 3. @section('breadcrumbs')
| - Untuk menampilkan breadcrumb di atas konten utama.
| - Jika tidak diisi, breadcrumb tidak akan ditampilkan.
|
| 4. @section('bookshelf-title', 'Judul Section')
| - Menentukan judul utama pada bagian konten koleksi.
| - Default: 'Koleksi Saya'
|
| 5. @section('bookshelf-content')
| - Menentukan konten utama halaman koleksi buku.
| - Wajib diisi di setiap halaman turunan layout ini.
|
| Sidebar:
| - Sidebar disusun otomatis dan menampilkan 3 tab:
| | - Koleksi Saya => route: bookshelf.index
| | - Lanjut Baca => route: bookshelf.continue
| | - Ulasan => route: bookshelf.reviews
|-------------------------------------------------------------------------------------
--}}


@extends('layouts.app')

@section('title', 'Koleksi Saya')

@section('navbar', view('layouts.navbar'))

@section('content')
    <div class="flex space-x-2 md:space-x-3 p-2 md:p-3">
        <aside
            class="max-h-fit md:w-1/5 p-1 md:p-2 md:space-y-2 rounded-lg shadow bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
            <x-ui.sidebar activeColor="secondary" :items="[
            [
                'label' => 'Koleksi Saya',
                'url' => 'bookshelf.index',
                'icon' => 'icons.bookmark',
            ],
            [
                'label' => 'Riwayat Baca',
                'url' => 'bookshelf.history',
                'icon' => 'icons.history',
            ],
            [
                'label' => 'Ulasan',
                'url' => 'bookshelf.reviews',
                'icon' => 'icons.star',
            ],
        ]" />
        </aside>

        <div class="flex-1 flex flex-col space-y-2">
            @hasSection('breadcrumbs')
                <section id="breadcrumbs">
                    @yield('breadcrumbs')
                </section>
            @endif
            <section id="content"
                class="p-2 md:p-3 space-y-2 rounded-lg shadow bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
                <div class="font-bold text-heading-sm md:text-heading-md">
                    @yield('bookshelf-title', 'Koleksi Saya')
                </div>
                <div class="text-body-sm md:text-body-md space-y-2">
                    @yield('bookshelf-content')
                </div>
            </section>
        </div>
    </div>
@endsection