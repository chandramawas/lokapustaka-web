@extends('layouts.app')

@section('title', 'Masuk - Lokapustaka')

@section('content')
    <div class="relative flex flex-col items-center justify-center min-h-screen">
        <div class="absolute top-2 left-2">
            <x-buttons.theme-toggle />
        </div>

        {{-- CONTENT CENTER --}}
        <div class="p-4 max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-6">
            {{-- KIRI --}}
            <div class="flex flex-col justify-center items-center md:items-start">
                <a href="{{ route('home') }}"
                    class="font-bold text-heading-lg md:text-heading-xl lg:text-display-md text-primary dark:text-primary-dark hover:scale-105 transition">
                    Lokapustaka</a>
                <p class="text-body-xl md:text-heading-sm lg:text-heading-md">Baca buku sepuasnya. Kapan aja, dimana
                    aja.</p>
            </div>

            {{-- KANAN --}}
            <div class="space-y-2">
                {{-- LOGIN CONTAINER --}}
                <div
                    class="flex flex-col gap-3 p-2 rounded-lg shadow-lg text-body-md md:text-body-lg bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
                    {{-- TITLE --}}
                    <div class="flex flex-col items-center">
                        <x-icons.logo />
                        <h3 class="font-bold">Masuk</h3>
                    </div>

                    {{-- LOGIN FORM --}}
                    <form action="{{ route('login') }}" method="post" class="flex flex-col gap-2">
                        @csrf
                        <x-forms.input type="email" name="email" placeholder="Email" required autofocus />

                        <x-forms.input type="password" name="password" placeholder="Password" required />

                        <a href="#" class="text-right text-label text-primary dark:text-primary-dark hover:underline">Lupa
                            Kata Sandi?</a>
                        <x-buttons.button type="submit" variant="primary-lg">Masuk</x-buttons.button>
                    </form>
                </div>

                {{-- REDIR REGISTER --}}
                <div class="space-y-1">
                    <p class="text-label text-center">Baru di Lokapustaka?</p>
                    <x-buttons.button href="{{ route('register') }}" variant="outline">Daftar Akun
                        Sekarang</x-buttons.button>
                </div>
            </div>
        </div>
    </div>
@endsection