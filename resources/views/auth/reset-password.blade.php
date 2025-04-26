@extends('layouts.app-auth')

@section('title', 'Reset Kata Sandi')

@section('auth-title', 'Reset Kata Sandi')

@section('auth-form')
    <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-2">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-forms.input type="email" name="email" placeholder="Email" autofocus />

        <x-forms.input type="password" name="password" placeholder="Password Baru" autofocus />

        <x-forms.input type="password" name="password_confirmation" placeholder="Konfirmasi Password" autofocus />

        <x-buttons.button type="submit" variant="primary-lg">Reset Password</x-buttons.button>
    </form>
@endsection

@section('auth-redirect')
    <p class="text-label text-center">Sudah punya akun? <a href="{{ route('login') }}"
            class="text-primary dark:text-primary-dark hover:underline">Masuk</a></p>
@endsection