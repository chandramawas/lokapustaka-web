<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Lokapustaka - Baca Buku Digital Tanpa Batas')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- VITE --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

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

        <section id="hero" class="hidden md:block">
            @yield('hero')
        </section>

        <section id="breadcrumbs">
            @yield('breadcrumbs')
        </section>

        <main class="flex-grow">
            @yield('content')
        </main>
    </div>
    <footer>
        @include('layouts.footer')
    </footer>
</body>

</html>