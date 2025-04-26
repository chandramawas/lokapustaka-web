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