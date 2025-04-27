@extends('layouts.app-account')

@section('title', 'Pengaturan Akun')

@section('breadcrumbs')
    <x-ui.breadcrumbs :items="[
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Akun Saya', 'url' => route('account.index')],
            ['label' => 'Pengaturan Akun', 'url' => route('account.settings')],
        ]" />
@endsection

@section('account-title', 'Pengaturan Akun')

@section('account-content')
    <form action="{{ route('account.update') }}" class="space-y-3" method="POST">
        @if (session('update-success'))
            <x-forms.label variant="success" textAlign="left">
                {{ session('update-success') }}
            </x-forms.label>
        @endif
        <div class="space-y-2">
            @csrf
            <!-- Nama -->
            <x-forms.input type="text" name="name" :value="$user->name" size="sm" label="Nama" placeholder="Masukkan Nama"
                autofocus />

            <!-- Jenis Kelamin -->
            <x-forms.select name="gender" :value="$user->gender" size="sm" label="Jenis Kelamin"
                placeholder="Pilih Jenis Kelamin" :options="[
            'Laki-Laki' => 'Laki-Laki',
            'Perempuan' => 'Perempuan',
            'Lainnya' => 'Lainnya',
        ]" />

            <!-- Tanggal Lahir -->
            <x-forms.input type="date" name="birthdate" :value="$user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : ''" size="sm" label="Tanggal Lahir"
                placeholder="Pilih Tanggal Lahir" />
        </div>

        <!-- Tombol Submit -->
        <div class=" flex justify-end">
            <x-buttons.button type="submit">Simpan Perubahan</x-buttons.button>
        </div>
    </form>
@endsection