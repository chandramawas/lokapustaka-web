{{--
Layout ini adalah template khusus untuk halaman-halaman otentikasi (login, register, reset password) Lokapustaka.
Layout ini mewarisi struktur dari layouts.app, jadi beberapa bagian dari app.blade.php tetap tersedia.
*
* Cara Pakai:
* @extends('layouts.app-auth')
*
* Bagian dari app.blade.php yang masih bisa digunakan:
* 1. @section('title')
* - Untuk mengatur title tab browser.
* - Default: 'Baca Buku Digital Tanpa Batas'.
*
* Bagian spesifik app-auth yang bisa diisi:
* 2. @section('auth-title')
* - Judul besar di atas form auth (contoh: "Masuk ke Akun Anda").
* - Wajib diisi.
*
* 3. @section('auth-form')
* - Kontainer form untuk login, register, dll.
* - Wajib diisi.
*
* 4. @section('auth-redirect')
* - Link navigasi tambahan (contoh: "Belum punya akun? Daftar").
* - Opsional.
*
* Fitur tambahan:
* - Tombol toggle tema di pojok kiri atas.
* - Branding Lokapustaka + tagline di sisi kiri untuk tampilan desktop.
--}}

@extends('layouts.app')

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
                {{-- CONTAINER --}}
                <div
                    class="flex flex-col gap-3 p-2 rounded-lg shadow-lg text-body-md md:text-body-lg bg-surface-container dark:bg-surface-container-dark text-on-surface dark:text-on-surface-dark">
                    {{-- TITLE --}}
                    <div class="flex flex-col items-center">
                        <x-icons.logo />
                        <h3 class="font-bold">@yield('auth-title')</h3>
                    </div>

                    {{-- FORM --}}
                    @yield('auth-form')
                </div>

                {{-- REDIR --}}
                @hasSection('auth-redirect')
                    <div class="space-y-1">
                        @yield('auth-redirect')
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection