@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div
            class="max-w-md w-full p-6 bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark rounded-lg shadow space-y-2">
            <div class="text-center space-y-2">
                <h1 class="text-heading-md font-bold">
                    Verifikasi Email
                </h1>
                <p class="text-body-md">
                    Link verifikasi sudah dikirim ke email. Cek inbox atau folder spam.
                </p>
            </div>

            <div>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-buttons.button type="submit" variant="primary-lg" class="w-full">
                        Kirim Ulang Email Verifikasi
                    </x-buttons.button>
                </form>
                @if (session('new-verif-link'))
                    <x-forms.label variant="success" textAlign="center">
                        Link verifikasi baru sudah dikirim ke email.
                    </x-forms.label>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-buttons.button type="submit" variant="text" class="w-full">
                    Ganti Akun
                </x-buttons.button>
            </form>
        </div>
    </div>
@endsection