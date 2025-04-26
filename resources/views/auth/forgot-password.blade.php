@extends('layouts.app-auth')

@section('title', 'Lupa Kata Sandi')

@section('auth-title', 'Lupa Kata Sandi')

@section('auth-form')
    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-2">
        @csrf
        <x-forms.input type="email" name="email" placeholder="Email" autofocus />
        @if (session('reset-link-sent'))
            <x-forms.label variant="success" textAlign="center">
                {{ session('reset-link-sent') }}
            </x-forms.label>
        @endif
        <x-buttons.button type="submit" variant="primary-lg">Kirim Link Reset Password</x-buttons.button>
    </form>
@endsection

@section('auth-redirect')
    <p class="text-label text-center">Baru di Lokapustaka?</p>
    <x-buttons.button href="{{ route('register') }}" variant="outline">Daftar Akun
        Sekarang</x-buttons.button>
@endsection