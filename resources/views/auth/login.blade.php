@extends('layouts.app-auth')

@section('title', 'Masuk')

@section('auth-title', 'Masuk')

@section('auth-form')
    <form action="{{ route('login') }}" method="post" class="flex flex-col gap-2">
        @csrf
        <x-forms.input type="email" name="email" placeholder="Email" autofocus />

        <x-forms.input type="password" name="password" placeholder="Password" />

        <a href="{{ route('password.request') }}"
            class="text-right text-label text-primary dark:text-primary-dark hover:underline">Lupa
            Kata Sandi?</a>
        <x-buttons.button type="submit" variant="primary-lg">Masuk</x-buttons.button>
    </form>
@endsection

@section('auth-redirect')
    <p class="text-label text-center">Baru di Lokapustaka?</p>
    <x-buttons.button href="{{ route('register') }}" variant="outline">Daftar Akun
        Sekarang</x-buttons.button>
@endsection