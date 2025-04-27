@extends('layouts.app-account')

@section('title', 'Ubah Kata Sandi')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Akun Saya', 'url' => route('account.index')],
            ['label' => 'Ubah Kata Sandi', 'url' => route('account.change-password')],
        ]" />
@endsection

@section('account-title', 'Ubah Kata Sandi')

@section('account-content')
    <form action="{{ route('account.update-password') }}" method="POST" class="space-y-3">
        @if (session('update-success'))
            <x-forms.label variant="success" textAlign="left">
                {{ session('update-success') }}
            </x-forms.label>
        @endif
        <div class="space-y-2">
            @csrf
            <!-- Kata Sandi Lama -->
            <x-forms.input type="password" name="current_password" placeholder="Kata Sandi Lama" autofocus />

            <!-- Kata Sandi Baru -->
            <x-forms.input type="password" name="password" placeholder="Kata Sandi Baru" />

            <!-- Konfirmasi Kata Sandi Baru -->
            <x-forms.input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru" />
        </div>
        <!-- Tombol Submit -->
        <div class="flex justify-end">
            <x-buttons.button type="submit">Simpan Perubahan</x-buttons.button>
        </div>
    </form>
@endsection