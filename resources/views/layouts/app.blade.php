{{--
|--------------------------------------------------------------------------------
| Lokapustaka - Layout Template
|--------------------------------------------------------------------------------
| Deskripsi:
| Template dasar untuk semua halaman di aplikasi Lokapustaka.
|--------------------------------------------------------------------------------
| Cara Pakai:
| @extends('layouts.app')
|
| Bagian yang bisa diisi:
|
| 1. @section('title', 'Judul Halaman')
| - Menentukan title di tab browser.
| - Default: 'Baca Buku Digital Tanpa Batas' jika tidak diisi.
|
| 2. @section('navbar')
| - Menampilkan navbar di atas halaman.
| - Opsional. Jika tidak diisi, navbar tidak akan muncul.
|
| 3. @section('content')
| - Konten utama halaman.
| - Wajib diisi di setiap halaman yang menggunakan layout ini.
|
| 4. @section('footer')
| - Menyesuaikan footer halaman.
| - Default: view('layouts.footer') jika tidak diisi.
|--------------------------------------------------------------------------------
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Baca Buku Digital Tanpa Batas') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- VITE --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- PUSH --}}
    @stack('scripts')
    @stack('styles')

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @livewireStyles
</head>

<body
    class="antialiased cursor-default bg-surface-container-high dark:bg-surface-container-high-dark text-on-surface dark:text-on-surface-dark">
    <div class="flex flex-col min-h-screen">
        <header class="sticky top-0 z-40">
            @yield('navbar')
        </header>

        <main class="flex flex-col flex-grow">
            @yield('content')
        </main>
    </div>
    <footer>
        @yield('footer', view('layouts.footer'))
    </footer>

    @livewireScripts
</body>

</html>