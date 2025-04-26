@extends('layouts.app-auth')

@section('title', 'Daftar')

@section('auth-title', 'Daftar')

@section('auth-form')
    <form action="{{ route('register') }}" method="post" class="flex flex-col gap-2">
        @csrf
        <x-forms.input type="email" name="email" placeholder="Email" autofocus />

        <x-forms.input type="text" name="name" placeholder="Nama Lengkap" />

        <x-forms.input type="password" name="password" placeholder="Password" />
        <x-forms.input type="password" name="password_confirmation" placeholder="Konfirmasi Password" />

        <x-buttons.button type="submit" variant="primary-lg">Daftar</x-buttons.button>
    </form>
@endsection

@section('auth-redirect')
    <p class="text-label text-center">Sudah punya akun? <a href="{{ route('login') }}"
            class="text-primary dark:text-primary-dark hover:underline">Masuk</a></p>
@endsection