@extends('layouts.app')

@section('navbar', view('layouts.navbar'))

@section('content')
    {{-- HERO --}}
    <section id="hero" class="md:min-h-[calc(100vh-4rem)] grid grid-cols-1 md:grid-cols-2">
        {{-- KIRI --}}
        <div class="p-3 md:p-4 lg:p-5 xl:p-6 space-y-3 lg:space-y-5">
            {{-- WELCOME --}}
            <div class="flex flex-col space-y-3">
                <div class="flex flex-col space-y-1 text-center md:text-start">
                    <h1 class="font-bold text-heading-md md:text-heading-lg lg:text-heading-xl">
                        Selamat Datang di Lokapustaka</h1>
                    <p class="font-medium text-body-lg md:text-body-xl lg:text-heading-sm">
                        Baca buku sepuasnya. Kapan aja, dimana aja.</p>
                </div>
                <p class="text-label md:text-body-sm lg:text-body-md">
                    Langganan mulai dari Rp20.000/bulan. Hemat hingga 69% dengan langganan tahunan hanya Rp75.000/tahun.</p>
            </div>
            {{-- PRICING --}}
            <div class="flex flex-col justify-center space-y-2 lg:space-y-2">
                <div class="hover:scale-105 transition">
                    @auth
                        <x-buttons.button :href="route('subscription.index')" variant="secondary-lg"
                            aria-label="Langganan Bulanan">Bergabung dengan Lokapustaka di Rp20.000/bulan</x-buttons.button>
                    @endauth
                    @guest
                        <x-buttons.button :href="route('register')" variant="secondary-lg"
                            aria-label="Langganan Bulanan">Bergabung dengan Lokapustaka di Rp20.000/bulan</x-buttons.button>
                    @endguest
                </div>
                <p class="text-center text-body-lg">atau</p>
                <div class="relative hover:scale-105 transition">
                    <span
                        class="absolute -top-2 -right-1 translate-x-1 bg-secondary text-on-secondary dark:bg-secondary-dark dark:text-on-secondary-dark text-label p-1 rounded-full shadow">
                        -69%
                    </span>
                    @auth
                        <x-buttons.button :href="route('subscription.index')" variant="primary-lg"
                            aria-label="Langganan Tahunan">Rp75.000/tahun</x-buttons.button>
                    @endauth
                    @guest
                        <x-buttons.button :href="route('register')" variant="primary-lg"
                            aria-label="Langganan Tahunan">Rp75.000/tahun</x-buttons.button>
                    @endguest
                </div>
            </div>

            @guest
                <p class="text-label">Anggota Lokapustaka?
                    <span class="inline-flex"><x-buttons.text-button :href="route('login')"
                            underlineHover>Masuk</x-buttons.text-button>
                    </span>
                </p>
            @endguest
        </div>

        {{-- KANAN --}}
        <div class="hidden md:flex justify-end p-3 bg-gradient-to-l from-primary">
            <div class="grid grid-cols-3 gap-3 max-h-full my-auto">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="rounded-md shadow-sm overflow-hidden hover:scale-105 transition">
                        <img src="https://placehold.co/150x220?text=Poster+{{ $i }}"
                            class="size-full aspect-[2/3] object-cover">
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <hr class="md:hidden my-3 border-outline-variant dark:border-outline-variant-dark">

    {{-- FEATURES --}}
    <section id="features" class="max-w-6xl mx-auto p-3 md:p-4 lg:p-5 xl:p-6 space-y-4 md:space-y-5">
        <h3 class="font-bold text-heading-sm md:text-heading-md">Kenapa Harus Lokapustaka?</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
            {{-- FEATURE 1 --}}
            <x-cards.feature-card title="Akses Semua Buku"
                description="Baca berbagai genre dari fiksi, non-fiksi, buku pelajaran, sampai buku pengembangan diri — semuanya dalam satu platform.">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-full">
                    <path
                        d="M270-80q-45 0-77.5-30.5T160-186v-558q0-38 23.5-68t61.5-38l395-78v640l-379 76q-9 2-15 9.5t-6 16.5q0 11 9 18.5t21 7.5h450v-640h80v720H270Zm90-233 200-39v-478l-200 39v478Zm-80 16v-478l-15 3q-11 2-18 9.5t-7 18.5v457q5-2 10.5-3.5T261-293l19-4Zm-40-472v482-482Z" />
                </svg>
            </x-cards.feature-card>

            {{-- FEATURE 2 --}}
            <x-cards.feature-card title="Harga Terjangkau"
                description="Langganan mulai dari Rp20.000/bulan. Lebih hemat dari satu kopi! Bisa juga langganan tahunan cuma Rp75.000.">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-full">
                    <path
                        d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                </svg>
            </x-cards.feature-card>

            {{-- FEATURE 3 --}}
            <x-cards.feature-card title="Nyaman untuk Membaca"
                description="Mode terang & gelap, ukuran teks bisa diatur, dan bebas iklan. Fokus baca tanpa gangguan.">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-full">
                    <path
                        d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z" />
                </svg>
            </x-cards.feature-card>
        </div>
    </section>

    <hr class="md:hidden my-3 border-outline-variant dark:border-outline-variant-dark">

    {{-- FEATURED BOOKS --}}
    <div class="max-w-6xl mx-auto p-3 md:p-4 lg:p-5 xl:p-6 space-y-2 md:space-y-3">
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
        <x-ui.book-carousel sectionName="featured" title="Koleksi Unggulan" :books="$books" />
    </div>

@endsection