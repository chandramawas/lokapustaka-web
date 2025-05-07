{{--
|--------------------------------------------------------------------------------
| Lokapustaka - Layout Template
|--------------------------------------------------------------------------------
| Deskripsi:
| Layout ini adalah template dasar untuk semua halaman di aplikasi Lokapustaka.
|--------------------------------------------------------------------------------
| Cara Pakai:
| @extends('layouts.app')
|
| Bagian yang bisa diisi:
|
| 1. @section('title', 'Judul Halaman')
| - Ini akan jadi title di tab browser.
| - Jika tidak diisi, akan pakai default: 'Baca Buku Digital Tanpa Batas'.
|
| 2. @section('navbar', 'view('layouts.navbar'))
| - Untuk menampilkan navbar di atas halaman.
| - Bersifat opsional. Kalau tidak diisi, navbar tidak akan muncul.
|
| 3. @section('content')
| - Ini adalah konten utama halaman.
| - Wajib diisi di setiap page yang extend layout ini.
|
| Footer:
| - Footer otomatis ditampilkan di bawah semua halaman.
| - Tidak perlu diisi manual.
|---------------------------------------------------------------------------------}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        @include('layouts.footer')
    </footer>
</body>

</html>